<?php

namespace App\Controller;

use App\Entity\Entreprise;
use App\Form\EntrepriseType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/entreprise')]
class EntrepriseController extends AbstractController
{
    #[Route('/new', name: 'app_entreprise_new')]
    #[IsGranted('ROLE_PILOTE')]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $entreprise = new Entreprise();
        $form = $this->createForm(EntrepriseType::class, $entreprise);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($entreprise);
            $entityManager->flush();

            $this->addFlash('success', 'Entreprise créée avec succès.');
            return $this->redirectToRoute('app_dashboard');
        }

        return $this->render('entreprise/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }
} 