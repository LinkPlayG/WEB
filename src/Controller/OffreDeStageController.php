<?php

namespace App\Controller;

use App\Entity\OffreDeStage;
use App\Form\OffreDeStageType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/offre')]
class OffreDeStageController extends AbstractController
{
    #[Route('/new', name: 'app_offre_new')]
    #[IsGranted('ROLE_PILOTE')]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $offre = new OffreDeStage();
        $form = $this->createForm(OffreDeStageType::class, $offre);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($offre);
            $entityManager->flush();

            $this->addFlash('success', 'Offre de stage créée avec succès.');
            return $this->redirectToRoute('app_dashboard');
        }

        return $this->render('offre/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }
} 