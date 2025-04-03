<?php
namespace App\Controller;

use App\Entity\Etudiant;
use App\Entity\Pdf;
use App\Entity\PiloteDePromotion;
use App\Entity\Administrateur;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProfileController extends AbstractController
{
    #[Route('/profile', name: 'app_profile')]
    public function index(): Response
    {
        // Récupérer l'utilisateur connecté
        $user = $this->getUser();

        // Vérifier si un utilisateur est connecté
        if (!$user) {
            return $this->redirectToRoute('app_login');
        }

        // Récupérer le nom et prénom en fonction du type d'utilisateur
        if ($user instanceof Etudiant) {
            $nom = $user->getNomEtudiant();
            $prenom = $user->getPrenomEtudiant();
        } elseif ($user instanceof PiloteDePromotion) {
            $nom = $user->getNomPilote();
            $prenom = $user->getPrenomPilote();
        } elseif ($user instanceof Administrateur) {
            $nom = $user->getNomAdmin();
            $prenom = $user->getPrenomAdmin();
        }

        return $this->render('profile/index.html.twig', [
            'nom' => $nom,
            'prenom' => $prenom,
            'user' => $user
        ]);
    }
}
?>