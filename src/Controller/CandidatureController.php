<?php
// src/Controller/CandidatureController.php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Candidature;
use App\Entity\OffreDeStage;
use App\Entity\User;
use App\Entity\Etudiant;

class CandidatureController extends AbstractController
{
    #[Route('/candidatures', name: 'app_candidatures')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        /** @var User $user */
        $user = $this->getUser();
        
        if (!$user || !($user instanceof Etudiant)) {
            return $this->redirectToRoute('app_login');
        }

        $candidatures = $entityManager->getRepository(Candidature::class)->findBy(['etudiant' => $user]);

        return $this->render('candidature/index.html.twig', [
            'candidatures' => $candidatures,
            'activeFilter' => 'pending' // Filtre actif par défaut
        ]);
    }

    #[Route('/candidature/new/{id}', name: 'app_candidature_new', methods: ['POST'])]
    public function new(Request $request, OffreDeStage $offre, EntityManagerInterface $entityManager): JsonResponse
    {
        /** @var User $user */
        $user = $this->getUser();
        
        if (!$user || !($user instanceof Etudiant)) {
            return new JsonResponse(['error' => 'Vous devez être connecté en tant qu\'étudiant pour postuler'], Response::HTTP_FORBIDDEN);
        }

        // Vérifier si une candidature existe déjà
        $existingCandidature = $entityManager->getRepository(Candidature::class)->findOneBy([
            'etudiant' => $user,
            'offreDeStage' => $offre
        ]);

        if ($existingCandidature) {
            return new JsonResponse(['error' => 'Vous avez déjà postulé à cette offre'], Response::HTTP_BAD_REQUEST);
        }

        // Créer une nouvelle candidature
        $candidature = new Candidature();
        $candidature->setEtudiant($user);
        $candidature->setOffreDeStage($offre);
        // Le statut "En attente" est défini par défaut dans le constructeur de Candidature

        $entityManager->persist($candidature);
        $entityManager->flush();

        return new JsonResponse([
            'success' => true,
            'message' => 'Candidature enregistrée avec succès'
        ]);
    }
}

?>