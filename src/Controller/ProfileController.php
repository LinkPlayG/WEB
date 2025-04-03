<?php
namespace App\Controller;

use App\Entity\Etudiant;
use App\Entity\User;
use App\Entity\Pdf;
use App\Entity\PiloteDePromotion;
use App\Entity\Administrateur;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;

class ProfileController extends AbstractController
{
    #[Route('/profile', name: 'app_profile')]
    public function index(EntityManagerInterface $entityManager): Response
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

            $pdfs = $entityManager->getRepository(Pdf::class)->findBy(
                ['etudiant' => $user],
                ['id' => 'DESC']
            );
        } elseif ($user instanceof PiloteDePromotion) {
            $nom = $user->getNomPilote();
            $prenom = $user->getPrenomPilote();
            $pdfs = [];
        } elseif ($user instanceof Administrateur) {
            $nom = $user->getNomAdmin();
            $prenom = $user->getPrenomAdmin();
            $pdfs = [];
        }

        return $this->render('profile/index.html.twig', [
            'nom' => $nom,
            'prenom' => $prenom,
            'user' => $user,
            'pdfs' => $pdfs ?? []
        ]);
    }
    
#[Route('/profile/upload', name: 'app_profile_upload')]
public function uploadProfileImage(Request $request, EntityManagerInterface $entityManager): Response
{
    // Get the current user
    $user = $this->getUser();
    if (!$user) {
        return $this->redirectToRoute('app_login');
    }

    // Handle file upload
    $file = $request->files->get('profile_image');
    $file_size = filesize($file);
    if ($file) {
        // Validate file type
        $mimeType = $file->getMimeType();
        if ((strpos($mimeType, 'image/') !== 0) || $file_size > 16000000) {
            $this->addFlash('error', 'Le fichier doit être une image et avoir une taille inférieure à 65 Ko.');
            return $this->redirectToRoute('app_profile');
        }

        // Read file content into BLOB
        $fileContent = file_get_contents($file->getPathname());
        $user->setProfileImg($fileContent);
        
        // Save changes
        $entityManager->persist($user);
        $entityManager->flush();
        
        $this->addFlash('success', 'Photo de profil mise à jour avec succès.');
    }

    return $this->redirectToRoute('app_profile');
}

#[Route('/profile/image/{id}', name: 'app_profile_image')]
public function getProfileImage(User $user): Response
{
    $profileImg = $user->getProfileImg();
    
    if (!$profileImg) {
        throw $this->createNotFoundException('Aucune image de profil disponible.');
    }
    
    // Convert BLOB resource to string if needed
    if (is_resource($profileImg)) {
        $profileImg = stream_get_contents($profileImg);
    }
    
    // Create response with appropriate headers
    $response = new Response($profileImg);
    
    // Try to detect the image type and set the appropriate content type
    $finfo = new \finfo(FILEINFO_MIME_TYPE);
    $mimeType = $finfo->buffer($profileImg) ?: 'image/jpeg';
    
    $response->headers->set('Content-Type', $mimeType);
    return $response;
}
}
?>