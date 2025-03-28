<?php

namespace App\Controller;

use App\Entity\Entreprise;
use App\Entity\Adresse;
use App\Form\EntrepriseType;
use App\Repository\EntrepriseRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use App\DTO\EntrepriseDTO;

#[IsGranted('ROLE_ADMIN')]
class EntrepriseController extends AbstractController
{
    #[Route('/admin/entreprises', name: 'app_entreprises')]
    public function index(EntrepriseRepository $entrepriseRepository): Response
    {
        $entreprises = $entrepriseRepository->findAll();
        
        // Statistiques
        $stats = [
            'total' => count($entreprises),
            'actives' => count(array_filter($entreprises, fn($e) => $e->isActive())),
            'inactives' => count(array_filter($entreprises, fn($e) => !$e->isActive()))
        ];

        return $this->render('entreprise/index.html.twig', [
            'entreprises' => $entreprises,
            'stats' => $stats
        ]);
    }

    #[Route('/admin/entreprise/new', name: 'app_entreprise_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $dto = new EntrepriseDTO();
        $form = $this->createForm(EntrepriseType::class, $dto);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Créer une nouvelle adresse
            $adresse = new Adresse();
            $adresse->setRue($dto->getRue());
            $adresse->setCodePostal($dto->getCodePostal());
            $adresse->setVille($dto->getVille());
            $adresse->setPays($dto->getPays());

            // Persister l'adresse
            $entityManager->persist($adresse);
            $entityManager->flush();

            // Créer une nouvelle entreprise
            $entreprise = new Entreprise();
            $entreprise->setNom($dto->getNom());
            $entreprise->setSecteur($dto->getSecteur());
            $entreprise->setAdresse($adresse);
            $entreprise->setActive($dto->isActive());
            $entreprise->setTelephone($dto->getTelephone());
            $entreprise->setEmail($dto->getEmail());
            $entreprise->setDescription($dto->getDescription());

            // Persister l'entreprise
            $entityManager->persist($entreprise);
            $entityManager->flush();

            $this->addFlash('success', 'L\'entreprise a été créée avec succès.');
            return $this->redirectToRoute('app_entreprises');
        }

        return $this->render('entreprise/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/admin/entreprise/{id}', name: 'app_entreprise_show')]
    public function show(Entreprise $entreprise): Response
    {
        return $this->render('entreprise/show.html.twig', [
            'entreprise' => $entreprise
        ]);
    }

    #[Route('/admin/entreprise/{id}/edit', name: 'app_entreprise_edit')]
    public function edit(Request $request, Entreprise $entreprise, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(EntrepriseType::class, $entreprise);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            $this->addFlash('success', 'L\'entreprise a été modifiée avec succès.');
            return $this->redirectToRoute('app_entreprises');
        }

        return $this->render('entreprise/edit.html.twig', [
            'entreprise' => $entreprise,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/admin/entreprise/{id}/delete', name: 'app_entreprise_delete', methods: ['POST'])]
    public function delete(Entreprise $entreprise, EntityManagerInterface $entityManager): Response
    {
        $entityManager->remove($entreprise);
        $entityManager->flush();

        $this->addFlash('success', 'L\'entreprise a été supprimée avec succès.');
        return $this->redirectToRoute('app_entreprises');
    }
} 