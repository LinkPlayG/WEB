<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class DashboardController extends AbstractController
{
    #[Route('/dashboard', name: 'app_dashboard')]
    public function index(): Response
    {
        if ($this->isGranted('ROLE_ADMIN')) {
            return $this->render('dashboard/admin.html.twig');
        } elseif ($this->isGranted('ROLE_PILOTE')) {
            return $this->render('dashboard/pilote.html.twig');
        } elseif ($this->isGranted('ROLE_ETUDIANT')) {
            return $this->render('dashboard/etudiant.html.twig');
        }

        // Redirection vers la page de connexion si non connecté
        return $this->redirectToRoute('app_login');
    }
} 