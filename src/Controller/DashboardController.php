<?php

namespace App\Controller;

use App\Entity\Etudiant;
use App\Entity\PiloteDePromotion;
use App\Entity\Entreprise;
use App\Entity\OffreDeStage;
use App\Entity\Promotion;
use App\Entity\Candidature;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class DashboardController extends AbstractController
{
    #[Route('/dashboard', name: 'app_dashboard')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        if ($this->isGranted('ROLE_ADMIN')) {
            // Statistiques pour l'admin
            $stats = [
                'etudiants' => $entityManager->getRepository(Etudiant::class)->count([]),
                'pilotes' => $entityManager->getRepository(PiloteDePromotion::class)->count([]),
                'entreprises' => $entityManager->getRepository(Entreprise::class)->count([]),
                'offres' => $entityManager->getRepository(OffreDeStage::class)->count([])
            ];

            // Liste des étudiants avec leurs promotions
            $etudiants = $entityManager->getRepository(Etudiant::class)->findAll();
            
            // Distribution des étudiants par promotion
            $promotions = $entityManager->getRepository(Promotion::class)->findAll();
            $distributionPromos = [];
            foreach ($promotions as $promotion) {
                $distributionPromos[$promotion->getNom()] = count($promotion->getEtudiants());
            }

            // Offres de stage par mois
            $offres = $entityManager->getRepository(OffreDeStage::class)->findAll();
            $offresParMois = array_fill(1, 12, 0); // Initialise un tableau avec 12 mois à 0
            
            foreach ($offres as $offre) {
                if ($offre->getDateDebutStage()) {
                    $mois = (int)$offre->getDateDebutStage()->format('n');
                    $offresParMois[$mois]++;
                }
            }
            
            // Convertir en format attendu par le template
            $offresParMoisFormatted = [];
            foreach ($offresParMois as $mois => $nombre) {
                $offresParMoisFormatted[] = [
                    'mois' => $mois,
                    'nombre' => $nombre
                ];
            }

            return $this->render('dashboard/admin.html.twig', [
                'stats' => $stats,
                'etudiants' => $etudiants,
                'distributionPromos' => $distributionPromos,
                'offresParMois' => $offresParMoisFormatted
            ]);

        } elseif ($this->isGranted('ROLE_PILOTE')) {
            $pilote = $this->getUser();
            
            // Statistiques pour le pilote
            $stats = [
                'etudiants' => count($pilote->getPromotions()->first()->getEtudiants()),
                'offres' => count($pilote->getOffresDeStage()),
                'entreprises' => $entityManager->getRepository(Entreprise::class)->count([]),
                'etudiantsEnStage' => $entityManager->getRepository(Etudiant::class)
                    ->createQueryBuilder('e')
                    ->join('e.promotion', 'p')
                    ->where('p.pilote = :pilote')
                    ->andWhere('e.statut = :statut')
                    ->setParameter('pilote', $pilote)
                    ->setParameter('statut', 'En stage')
                    ->getQuery()
                    ->getResult()
            ];

            // Récupérer les étudiants du pilote
            $etudiants = $entityManager->getRepository(Etudiant::class)
                ->createQueryBuilder('e')
                ->join('e.promotion', 'p')
                ->where('p.pilote = :pilote')
                ->setParameter('pilote', $pilote)
                ->getQuery()
                ->getResult();

            return $this->render('dashboard/pilote.html.twig', [
                'stats' => $stats,
                'etudiants' => $etudiants
            ]);

        } elseif ($this->isGranted('ROLE_ETUDIANT')) {
            $etudiant = $this->getUser();
            
            // Statistiques pour l'étudiant
            $stats = [
                'candidatures' => $entityManager->getRepository(Candidature::class)
                    ->count(['etudiant' => $etudiant]),
                'entretiens' => $entityManager->getRepository(Candidature::class)
                    ->count(['etudiant' => $etudiant, 'statut' => 'Entretien']),
                'offresDisponibles' => $entityManager->getRepository(OffreDeStage::class)
                    ->count(['statut_offre' => 'Disponible']),
                'wishlist' => 0 // À implémenter avec la fonctionnalité wishlist
            ];

            // Récupérer les offres disponibles
            $offres = $entityManager->getRepository(OffreDeStage::class)
                ->findBy(['statut_offre' => 'Disponible']);

            // Récupérer les candidatures de l'étudiant
            $candidatures = $entityManager->getRepository(Candidature::class)
                ->findBy(['etudiant' => $etudiant]);

            return $this->render('dashboard/etudiant.html.twig', [
                'stats' => $stats,
                'offres' => $offres,
                'candidatures' => $candidatures
            ]);
        }

        return $this->redirectToRoute('app_login');
    }
} 