<?php
// src/Controller/CandidatureController.php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CandidatureController extends AbstractController
{
    #[Route('/candidatures', name: 'app_candidatures')]
    public function index(): Response
    {
        return $this->render('candidature/index.html.twig');
    }
}

?>