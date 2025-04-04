<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Etudiant;
use App\Entity\PiloteDePromotion;
use App\Entity\Administrateur;
use App\Entity\Promotion;
use App\Form\EtudiantType;
use App\Form\PiloteType;
use App\Form\UserType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Security\Core\Security;
use App\Repository\UserRepository;

#[IsGranted('ROLE_ADMIN')]
class UserController extends AbstractController
{
    #[Route('/admin/users', name: 'app_users')]
    public function index(UserRepository $userRepository): Response
    {
        $users = $userRepository->findAll();

        return $this->render('user/index.html.twig', [
            'users' => $users
        ]);
    }

    #[Route('/etudiant/new', name: 'app_etudiant_new')]
    #[IsGranted('ROLE_ADMIN')]
    public function newEtudiant(Request $request, EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordHasher): Response
    {
        $etudiant = new Etudiant();
        $form = $this->createForm(EtudiantType::class, $etudiant);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $hashedPassword = $passwordHasher->hashPassword(
                $etudiant,
                $form->get('password')->getData()
            );
            $etudiant->setPassword($hashedPassword);

            $entityManager->persist($etudiant);
            $entityManager->flush();

            $this->addFlash('success', 'Étudiant créé avec succès.');
            return $this->redirectToRoute('app_dashboard');
        }

        return $this->render('user/new_etudiant.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/pilote/new', name: 'app_pilote_new')]
    #[IsGranted('ROLE_ADMIN')]
    public function newPilote(Request $request, EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordHasher): Response
    {
        $pilote = new PiloteDePromotion();
        $form = $this->createForm(PiloteType::class, $pilote);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $hashedPassword = $passwordHasher->hashPassword(
                $pilote,
                $form->get('password')->getData()
            );
            $pilote->setPassword($hashedPassword);

            // Récupérer la promotion par défaut
            $defaultPromotion = $entityManager->getRepository(Promotion::class)
                ->findOneBy(['nom' => 'Promotion par défaut']);

            if ($defaultPromotion) {
                $pilote->addPromotion($defaultPromotion);
                $defaultPromotion->setPilote($pilote);
            }

            $entityManager->persist($pilote);
            $entityManager->flush();

            $this->addFlash('success', 'Pilote créé avec succès.');
            return $this->redirectToRoute('app_dashboard');
        }

        return $this->render('user/new_pilote.html.twig', [
            'form' => $form->createView(),
        ]);

    }

    #[Route('/etudiant/delete/{id}', name: 'app_etudiant_delete', methods: ['POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function deleteEtudiant(Request $request, Etudiant $etudiant, EntityManagerInterface $em): Response
    {
        if ($this->isCsrfTokenValid('delete_etudiant_' . $etudiant->getId(), $request->request->get('_token'))) {
            $em->remove($etudiant);
            $em->flush();
            $this->addFlash('success', 'Étudiant supprimé avec succès.');
        } else {
            $this->addFlash('error', 'Jeton CSRF invalide.');
        }

        return $this->redirectToRoute('app_users');
    }


    #[Route('/pilote/delete/{id}', name: 'app_pilote_delete', methods: ['POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function deletePilote(Request $request, PiloteDePromotion $pilote, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete_pilote_' . $pilote->getId(), $request->request->get('_token'))) {
            $entityManager->remove($pilote);
            $entityManager->flush();
            $this->addFlash('success', 'Le pilote a été supprimé.');
        } else {
            $this->addFlash('error', 'Jeton CSRF invalide.');
        }

        return $this->redirectToRoute('app_users');
    }

    #[Route('/{id}/edit', name: 'app_user_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {
        $originalRoles = $user->getRoles();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $newRoles = $user->getRoles();
            
            // Vérifier si le type d'utilisateur doit changer
            $shouldConvert = $this->shouldConvertUserType($originalRoles, $newRoles);
            
            if ($shouldConvert) {
                // Créer une nouvelle instance du bon type
                $newUser = $this->convertUserType($user, $newRoles);
                
                if ($newUser) {
                    try {
                        $entityManager->beginTransaction();
                        
                        // Supprimer d'abord l'ancien utilisateur
                        $entityManager->remove($user);
                        $entityManager->flush();
                        
                        // Puis créer le nouveau
                        $entityManager->persist($newUser);
                        $entityManager->flush();
                        
                        $entityManager->commit();
                        
                        $this->addFlash('success', 'L\'utilisateur a été converti et modifié avec succès.');
                        return $this->redirectToRoute('app_users');
                    } catch (\Exception $e) {
                        $entityManager->rollback();
                        $this->addFlash('error', 'Une erreur est survenue lors de la conversion de l\'utilisateur.');
                        return $this->redirectToRoute('app_users');
                    }
                }
            }
            
            // Si pas de conversion nécessaire, simplement sauvegarder les modifications
            $entityManager->flush();
            $this->addFlash('success', 'L\'utilisateur a été modifié avec succès.');
            return $this->redirectToRoute('app_users');
        }

        return $this->render('user/edit.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    private function shouldConvertUserType(array $originalRoles, array $newRoles): bool
    {
        $getMainRole = function($roles) {
            if (in_array('ROLE_ADMIN', $roles)) return 'ROLE_ADMIN';
            if (in_array('ROLE_PILOTE', $roles)) return 'ROLE_PILOTE';
            if (in_array('ROLE_ETUDIANT', $roles)) return 'ROLE_ETUDIANT';
            return 'ROLE_USER';
        };

        return $getMainRole($originalRoles) !== $getMainRole($newRoles);
    }

    private function convertUserType(User $oldUser, array $newRoles): ?User
    {
        // Déterminer la nouvelle classe basée sur le rôle principal
        $newUser = null;
        if (in_array('ROLE_ADMIN', $newRoles)) {
            $newUser = new Administrateur();
            // Copier les propriétés spécifiques de l'administrateur
            if ($oldUser instanceof Etudiant) {
                $newUser->setNomAdmin($oldUser->getNomEtudiant());
                $newUser->setPrenomAdmin($oldUser->getPrenomEtudiant());
            } elseif ($oldUser instanceof PiloteDePromotion) {
                $newUser->setNomAdmin($oldUser->getNomPilote());
                $newUser->setPrenomAdmin($oldUser->getPrenomPilote());
            } elseif ($oldUser instanceof Administrateur) {
                $newUser->setNomAdmin($oldUser->getNomAdmin());
                $newUser->setPrenomAdmin($oldUser->getPrenomAdmin());
            }
        } elseif (in_array('ROLE_PILOTE', $newRoles)) {
            $newUser = new PiloteDePromotion();
            // Copier les propriétés spécifiques du pilote
            if ($oldUser instanceof Etudiant) {
                $newUser->setNomPilote($oldUser->getNomEtudiant());
                $newUser->setPrenomPilote($oldUser->getPrenomEtudiant());
            } elseif ($oldUser instanceof Administrateur) {
                $newUser->setNomPilote($oldUser->getNomAdmin());
                $newUser->setPrenomPilote($oldUser->getPrenomAdmin());
            } elseif ($oldUser instanceof PiloteDePromotion) {
                $newUser->setNomPilote($oldUser->getNomPilote());
                $newUser->setPrenomPilote($oldUser->getPrenomPilote());
                // Copier les promotions si c'était déjà un pilote
                foreach ($oldUser->getPromotions() as $promotion) {
                    $newUser->addPromotion($promotion);
                }
            }
        } elseif (in_array('ROLE_ETUDIANT', $newRoles)) {
            $newUser = new Etudiant();
            // Copier les propriétés spécifiques de l'étudiant
            if ($oldUser instanceof Administrateur) {
                $newUser->setNomEtudiant($oldUser->getNomAdmin());
                $newUser->setPrenomEtudiant($oldUser->getPrenomAdmin());
            } elseif ($oldUser instanceof PiloteDePromotion) {
                $newUser->setNomEtudiant($oldUser->getNomPilote());
                $newUser->setPrenomEtudiant($oldUser->getPrenomPilote());
            } elseif ($oldUser instanceof Etudiant) {
                $newUser->setNomEtudiant($oldUser->getNomEtudiant());
                $newUser->setPrenomEtudiant($oldUser->getPrenomEtudiant());
                $newUser->setPromotion($oldUser->getPromotion());
                $newUser->setStatut($oldUser->getStatut());
            }
        }

        if ($newUser) {
            // Copier les propriétés de base communes à tous les utilisateurs
            $newUser->setEmail($oldUser->getEmail());
            $newUser->setPassword($oldUser->getPassword());
            $newUser->setRoles($newRoles);
            $newUser->setDateCreation($oldUser->getDateCreation());
            $newUser->setProfileImg($oldUser->getProfileImg());
        }

        return $newUser;
    }
}