<?php
// src/Controller/WishlistController.php
namespace App\Controller;

use App\Entity\Wishlist;
use App\Entity\OffreDeStage;
use App\Entity\Etudiant;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/wishlist')]
#[IsGranted('ROLE_ETUDIANT')]
class WishlistController extends AbstractController
{
    #[Route('/', name: 'app_wishlist')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        /** @var Etudiant $etudiant */
        $etudiant = $this->getUser();
        
        $wishlistItems = $entityManager->getRepository(Wishlist::class)
            ->findByEtudiant($etudiant);
        
        $offres = array_map(fn($item) => $item->getOffreDeStage(), $wishlistItems);
        
        return $this->render('wishlist/index.html.twig', [
            'offres' => $offres
        ]);
    }

    #[Route('/add/{id}', name: 'app_wishlist_add', methods: ['POST'])]
    public function add(OffreDeStage $offre, EntityManagerInterface $entityManager): JsonResponse
    {
        /** @var Etudiant $etudiant */
        $etudiant = $this->getUser();
        
        // Vérifier si l'offre n'est pas déjà dans la wishlist
        $existingItem = $entityManager->getRepository(Wishlist::class)
            ->findOneByEtudiantAndOffre($etudiant, $offre);
        
        if (!$existingItem) {
            $wishlistItem = new Wishlist();
            $wishlistItem->setEtudiant($etudiant)
                        ->setOffreDeStage($offre);
            
            $entityManager->persist($wishlistItem);
            $entityManager->flush();
            
            return new JsonResponse(['success' => true]);
        }
        
        return new JsonResponse(['success' => false, 'message' => 'Déjà dans la wishlist']);
    }

    #[Route('/remove/{id}', name: 'app_wishlist_remove', methods: ['POST'])]
    public function remove(OffreDeStage $offre, EntityManagerInterface $entityManager): JsonResponse
    {
        /** @var Etudiant $etudiant */
        $etudiant = $this->getUser();
        
        $wishlistItem = $entityManager->getRepository(Wishlist::class)
            ->findOneByEtudiantAndOffre($etudiant, $offre);
        
        if ($wishlistItem) {
            $entityManager->remove($wishlistItem);
            $entityManager->flush();
            
            return new JsonResponse(['success' => true]);
        }
        
        return new JsonResponse(['success' => false, 'message' => 'Item non trouvé']);
    }

    #[Route('/check/{id}', name: 'app_wishlist_check', methods: ['GET'])]
    public function check(OffreDeStage $offre, EntityManagerInterface $entityManager): JsonResponse
    {
        /** @var Etudiant $etudiant */
        $etudiant = $this->getUser();
        
        $wishlistItem = $entityManager->getRepository(Wishlist::class)
            ->findOneByEtudiantAndOffre($etudiant, $offre);
        
        return new JsonResponse(['inWishlist' => $wishlistItem !== null]);
    }
}

?>