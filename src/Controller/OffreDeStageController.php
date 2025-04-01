<?php

namespace App\Controller;

use App\Entity\OffreDeStage;
use App\Entity\PiloteDePromotion;
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
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        if (!$this->isGranted('ROLE_ADMIN') && !$this->isGranted('ROLE_PILOTE')) {
            throw $this->createAccessDeniedException('Vous n\'avez pas les droits nécessaires pour créer une offre de stage.');
        }

        $offre = new OffreDeStage();
        
        // Si c'est un pilote qui crée l'offre, on l'assigne automatiquement
        $user = $this->getUser();
        if ($user instanceof PiloteDePromotion) {
            $offre->setPilote($user);
        }
        
        $form = $this->createForm(OffreDeStageType::class, $offre);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Vérifier que le pilote est défini
            if (!$offre->getPilote()) {
                $this->addFlash('error', 'Vous devez sélectionner un pilote responsable pour cette offre.');
                return $this->render('offre/new.html.twig', [
                    'form' => $form->createView(),
                ]);
            }
            
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