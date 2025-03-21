<?php

namespace App\Controller;

use App\Entity\Etudiant;
use App\Entity\PiloteDePromotion;
use App\Entity\Promotion;
use App\Form\EtudiantType;
use App\Form\PiloteType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/admin')]
class UserController extends AbstractController
{
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
} 