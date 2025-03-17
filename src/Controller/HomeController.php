<?php
// src/Controller/HomeController.php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
#[Route('/', name: 'app_home')]
public function index(): Response
{
// Texte "À propos de nous"
$aboutText = "Interned est une plateforme dédiée à la recherche de stages pour les étudiants. Notre mission est de connecter les étudiants avec les entreprises qui proposent des stages correspondant à leurs compétences et aspirations. Nous facilitons le processus de recherche et de candidature pour permettre aux étudiants de trouver rapidement des opportunités pertinentes.";

// Données statiques pour les offres
$offers = [
[
'id' => 1,
'name' => 'Stage développeur web',
'description' => 'Stage de 6 mois en développement web avec des technologies modernes (React, Node.js).'
],
[
'id' => 2,
'name' => 'Stage marketing digital',
'description' => 'Stage de 4 mois en marketing digital, spécialisé dans les réseaux sociaux et le SEO.'
],
[
'id' => 3,
'name' => 'Stage designer UX/UI',
'description' => 'Stage de 3 mois en conception d\'interfaces utilisateur et expérience utilisateur.'
],
[
'id' => 4,
'name' => 'Stage assistant comptable',
'description' => 'Stage de 6 mois au sein d\'un cabinet comptable pour assister l\'équipe dans ses missions quotidiennes.'
]
];

return $this->render('home/index.html.twig', [
'aboutText' => $aboutText,
'offers' => $offers,
]);
}
}
?>