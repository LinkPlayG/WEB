<?php

namespace App\Controller;


use App\Entity\Etudiant;
use App\Entity\Pdf;
use App\Form\PdfCVType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

class PdfUploadController extends AbstractController
{
    #[Route('/profile/CV', name: 'app_cv')]
    public function new(
        Request $request,
        SluggerInterface $slugger,
        EntityManagerInterface $entityManager,
        #[Autowire('%kernel.project_dir%/public/uploads')] string $pdfDirectory
    ): Response
    {
        $pdf = new Pdf();

        $form = $this->createForm(PdfCVType::class, $pdf);
        $form->handleRequest($request);
        

        $user = $this->getUser();
        $pdf->setEtudiant($user);
        if (!$user || !($user instanceof Etudiant)) {
            $this->addFlash('error', 'Vous devez être connecté en tant qu\'étudiant pour accéder à cette page.');
            return $this->redirectToRoute('app_login');
        }
        

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $cvFile */
            $cvFile = $form->get('CV')->getData();

            // this condition is needed because the 'CV' field is not required
            // so the PDF file must be processed only when a file is uploaded
            if ($cvFile) {
                $originalFilename = pathinfo($cvFile->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$cvFile->guessExtension();

                // Move the file to the directory where pdf are stored
                try {
                    $cvFile->move($pdfDirectory, $newFilename);
                } catch (FileException $e) {
                    // Add flash message for error
                    $this->addFlash('error', 'Une erreur est survenue lors du téléchargement du fichier.');
                    // You might want to log this exception
                }

                // updates the 'filename' property to store the PDF file name
                // instead of its contents
                $pdf->setFilename($newFilename);
                
                // Persist the entity to database
                $entityManager->persist($pdf);
                $entityManager->flush();
                
                $this->addFlash('success', 'Votre CV a été téléchargé avec succès.');
                
                // Redirect to same page to avoid form resubmission
                return $this->redirectToRoute('app_cv');
            }
        }

        $userPdfs = $entityManager->getRepository(Pdf::class)->findBy(
            ['etudiant' => $user],
            ['id' => 'DESC']
        );

        return $this->render('pdf_upload/index.html.twig', [
            'form' => $form,
            'pdfs' => $userPdfs, // Passer la liste complète des PDFs
            'pdf' => !empty($userPdfs) ? $userPdfs[0] : null
        ]);
    }
}
?>