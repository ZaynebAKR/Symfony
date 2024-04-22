<?php

namespace App\Controller;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Page;
use App\Form\PageType;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use App\Entity\Publication;
use App\Form\PublicationType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\PublicationRepository;
use App\Entity\Utilisateur;



class SuperAdminController extends AbstractController
{
     /********** Page  **********/
    #[Route('/Superadmin/Acceuil', name: 'app_Super_admin')]
    public function index(): Response
    {
        return $this->render('Superadmin/index.html.twig', [
            'controller_name' => 'SuperAdminController',
        ]);
    }
 #[Route('/page/new/{idU}', name: 'app_page_Super_admin_new', methods: ['GET', 'POST'])]
    public function newpage(Request $request, EntityManagerInterface $entityManager , $idU): Response
    {
    $page = new Page();
    $form = $this->createForm(PageType::class, $page);
    $form->handleRequest($request);
    
    if ($form->isSubmitted() && $form->isValid()) {
        $image = $form->get('image')->getData();
        if ($image) {
            // Generate a unique filename
            $originalFilename = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);
            $newFilename = $originalFilename.'-'.uniqid().'.'.$image->guessExtension();
    
            // Move the file to the directory where images are stored
            try {
                $image->move(
                    $this->getParameter('images_directory'),
                    $newFilename
                );
            } catch (FileException $e) {
                // Handle file upload exception
                $this->addFlash('error', 'An error occurred while uploading the image.');
                return $this->redirectToRoute('app_page_Super_admin_new');
            }
    
            // Set the image property in the entity to the relative path of the uploaded file
            $page->setImage('assets/img/'.$newFilename);
        }
    
                $logo = $form->get('logo')->getData();
                if ($logo) {
                    // Generate a unique filename
                    $originalFilename = pathinfo($logo->getClientOriginalName(), PATHINFO_FILENAME);
                    $newFilename = $originalFilename.'-'.uniqid().'.'.$logo->guessExtension();
        
                    // Move the file to the directory where logos are stored
                    try {
                        $logo->move(
                            $this->getParameter('logos_directory'),
                            $newFilename
                        );
                    } catch (FileException $e) {
                        // Handle file upload exception
                        $this->addFlash('error', 'An error occurred while uploading the logo.');
                        return $this->redirectToRoute('app_page_Super_admin_new');
                    }
        
                    // Set the logo property in the entity to the relative path of the uploaded file
                    $page->setLogo('assets/img/'.$newFilename);
                }
                $idu = 47; 
                $utilisateur = $entityManager->getReference(Utilisateur::class, $idu); // Debugging statement to check if the page is fetched correctly
                $page->setUserRelation($utilisateur);
    $entityManager->persist($page);
            $entityManager->flush();
    
            return $this->redirectToRoute('app_Super_admin_page', [], Response::HTTP_SEE_OTHER);
        }
    
    
    return $this->renderForm('Superadmin/Superadmin_page_new.html.twig', [
    'page' => $page,
        'form' => $form,
    ]);
    }
    #[Route('/Superadmin_Page', name: 'app_Super_admin_page')]
    public function indexx(EntityManagerInterface $entityManager): Response
    { 
        $pages = $entityManager
        ->getRepository(Page::class)
        ->findAll();
        return $this->render('Superadmin/Superadmin_page.html.twig', [
            'controller_name' => 'SuperAdminController',
            'pages' => $pages,
        ]);
    }
    
   

    #[Route('/Superadmin/Page/{idP}', name: 'app_Super_admin_show_page', methods: ['GET'])]
    public function showpage(Page $page): Response
    {
        return $this->render('Superadmin/Superadmin_show_page.html.twig', [
            'page' => $page,
        ]);
    }

    
    #[Route('/page/edit/{idP}', name: 'app_Super_admin_edit_page', methods: ['GET', 'POST'])]
    public function editpage(Request $request, Page $page , EntityManagerInterface $entityManager): Response
    {
        // Create the form, passing the Page entity
        $form = $this->createForm(PageType::class, $page);

        // Handle form submission
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $image = $form->get('image')->getData();
            if ($image) {
                // Generate a unique filename
                $originalFilename = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);
                $newFilename = $originalFilename.'-'.uniqid().'.'.$image->guessExtension();
    
                // Move the file to the directory where images are stored
                try {
                    $image->move(
                        $this->getParameter('images_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // Handle file upload exception
                    $this->addFlash('error', 'An error occurred while uploading the image.');
                    return $this->redirectToRoute('app_Super_admin_edit_page');
                }
    
                // Set the image property in the entity to the relative path of the uploaded file
                $page->setImage('assets/img/'.$newFilename);
            }
            $logo = $form->get('logo')->getData();
            if ($logo) {
                // Generate a unique filename
                $originalFilename = pathinfo($logo->getClientOriginalName(), PATHINFO_FILENAME);
                $newFilename = $originalFilename.'-'.uniqid().'.'.$logo->guessExtension();
    
                // Move the file to the directory where logos are stored
                try {
                    $logo->move(
                        $this->getParameter('logos_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // Handle file upload exception
                    $this->addFlash('error', 'An error occurred while uploading the logo.');
                    return $this->redirectToRoute('app_Super_admin_edit_page');
                }
    
                // Set the logo property in the entity to the relative path of the uploaded file
                $page->setLogo('assets/img/'.$newFilename);
            }
            // Save the changes to the database
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->flush();

            // Redirect to the page show route after successful edit
            return $this->redirectToRoute('app_Super_admin_page', [] , Response::HTTP_SEE_OTHER);

        }

        // Render the form template, passing the form and page variables to the template
        return $this->render('Superadmin/Superadmin_edit_page.html.twig', [
            'form' => $form->createView(),
            'page' => $page, // Pass the Page entity to the template
        ]);
    }

    #[Route('/{idP}', name: 'app_Super_admin_delete_page', methods: ['POST'])]
    public function deletepage(Request $request, Page $page, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$page->getIdp(), $request->request->get('_token'))) {
            $entityManager->remove($page);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_Super_admin_page', [], Response::HTTP_SEE_OTHER);
    }

         /********** Publication  **********/

         #[Route('/publication/new/{idPP}', name: 'app_publication_Super_admin_new', methods: ['GET', 'POST'])]
         public function new(Request $request, EntityManagerInterface $entityManager , $idPP): Response
     
         {
             $publication = new Publication();
             $form = $this->createForm(PublicationType::class, $publication);
             $form->handleRequest($request);
     
             if ($form->isSubmitted() && $form->isValid()) {
                  $image = $form->get('image')->getData();
         if ($image) {
             // Generate a unique filename
             $originalFilename = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);
             $newFilename = $originalFilename.'-'.uniqid().'.'.$image->guessExtension();
     
             // Move the file to the directory where images are stored
             try {
                 $image->move(
                     $this->getParameter('images_directory'),
                     $newFilename
                 );
             } catch (FileException $e) {
                 // Handle file upload exception
                 $this->addFlash('error', 'An error occurred while uploading the image.');
                 return $this->redirectToRoute('app_publication_Super_admin_new');
             }
     
             // Set the image property in the entity to the relative path of the uploaded file
             $publication->setImage('assets/img/'.$newFilename);
         }
         $idPP = 289; 
         $page = $entityManager->getReference(Page::class, $idPP); // Debugging statement to check if the page is fetched correctly
         $publication->setPageRelation($page);
                 $entityManager->persist($publication);
                 $entityManager->flush();
     
                 return $this->redirectToRoute('app_Super_admin_publication', [], Response::HTTP_SEE_OTHER);
             }
     
             return $this->renderForm('Superadmin/Superadmin_publication_new.html.twig', [
                 'publication' => $publication,
                 'form' => $form,
             ]);
         }
     
    #[Route('/Superadmin/Pub', name: 'app_Super_admin_publication')]
    public function indexxx(EntityManagerInterface $entityManager): Response
    {
        $publications = $entityManager
            ->getRepository(Publication::class) // Fetch publications instead of pages
            ->findAll();
        
        return $this->render('Superadmin/Superadmin_publication.html.twig', [
            'controller_name' => 'SuperAdminController',
            'publications' => $publications,
        ]);
    }



    #[Route('/Superadmin/Pub/{id_P}/edit', name: 'app_Super_admin_edit', methods: ['GET', 'POST'])]
    public function editpub(Request $request, Publication $publication, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(PublicationType::class, $publication);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $image = $form->get('image')->getData();
            if ($image) {
                // Generate a unique filename
                $originalFilename = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);
                $newFilename = $originalFilename.'-'.uniqid().'.'.$image->guessExtension();
    
                // Move the file to the directory where images are stored
                try {
                    $image->move(
                        $this->getParameter('images_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // Handle file upload exception
                    $this->addFlash('error', 'An error occurred while uploading the image.');
                    return $this->redirectToRoute('app_Super_admin_edit');
                }
    
                // Set the image property in the entity to the relative path of the uploaded file
                $publication->setImage('assets/img/'.$newFilename);
            }
            $entityManager->flush();

            return $this->redirectToRoute('app_Super_admin_publication', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('Superadmin/Superadmin_edit.html.twig', [
            'publication' => $publication,
            'form' => $form,
        ]);
    }

    #[Route('/Superadmin/Pub/{id_P}', name: 'app_Super_admin_delete', methods: ['POST'])]
    public function deletepub(Request $request, Publication $publication, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$publication->getId_P(), $request->request->get('_token'))) {
            $entityManager->remove($publication);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_Super_admin_publication', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/Superadmin/Pub/{id_P}', name: 'app_Super_admin_show', methods: ['GET'])]
    public function showpubid(Publication $publication): Response
    {
        return $this->render('Superadmin/Superadmin_show.html.twig', [
            'publication' => $publication,
        ]);
    }


}
