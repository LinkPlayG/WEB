<?php
// src/Controller/AnnonceController.php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AnnonceController extends AbstractController
{
#[Route('/annonce', name: 'app_annonces')]
public function index(): Response
{
return $this->render('annonce/index.html.twig');
}
}
?>
