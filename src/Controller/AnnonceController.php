<?php
// src/Controller/AnnonceController.php
namespace App\Controller;

use App\Repository\OffreDeStageRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AnnonceController extends AbstractController
{
    #[Route(['/annonce', '/annonces'], name: 'app_annonces')]
    public function index(OffreDeStageRepository $offreDeStageRepository): Response
    {
        $offres = $offreDeStageRepository->findBy(
            ['statut_offre' => 'Disponible'],
            ['date_debut_stage' => 'DESC']
        );

        // Debug: afficher les coordonnées des entreprises
        foreach ($offres as $offre) {
            $adresse = $offre->getEntreprise()->getAdresse();
            if ($adresse) {
                dump([
                    'entreprise' => $offre->getEntreprise()->getNom(),
                    'rue' => $adresse->getRue(),
                    'ville' => $adresse->getVille(),
                    'latitude' => $adresse->getLatitude(),
                    'longitude' => $adresse->getLongitude(),
                ]);
            }
        }

        return $this->render('annonce/index.html.twig', [
            'offres' => $offres,
        ]);
    }
}
?>
