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



class SuperAdminController extends AbstractController
{
    #[Route('/Superadmin/Acceuil', name: 'app_Super_admin')]
    public function index(): Response
    {
        return $this->render('Superadmin/index.html.twig', [
            'controller_name' => 'SuperAdminController',
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
    


    #[Route('/Superadmin/Page/{idp}', name: 'app_Super_admin_show_page', methods: ['GET'])]
    public function showpage(Page $page): Response
    {
        return $this->render('Superadmin/Superadmin_show_page.html.twig', [
            'page' => $page,
        ]);
    }

    
    #[Route('/page/edit/{idp}', name: 'app_Super_admin_edit_page', methods: ['GET', 'POST'])]
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
                    return $this->redirectToRoute('app_defis_new');
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
                    return $this->redirectToRoute('app_defis_new');
                }
    
                // Set the logo property in the entity to the relative path of the uploaded file
                $page->setLogo('assets/img/'.$newFilename);
            }
            // Save the changes to the database
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->flush();

            // Redirect to the page show route after successful edit
            return $this->redirectToRoute('app_Super_admin_show_page');
        }

        // Render the form template, passing the form and page variables to the template
        return $this->render('Superadmin/Superadmin_edit.html.twig', [
            'form' => $form->createView(),
            'page' => $page, // Pass the Page entity to the template
        ]);
    }

    #[Route('/{idp}', name: 'app_Super_admin_delete_page', methods: ['POST'])]
    public function deletepage(Request $request, Page $page, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$page->getIdp(), $request->request->get('_token'))) {
            $entityManager->remove($page);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_Super_admin_page', [], Response::HTTP_SEE_OTHER);
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



    #[Route('/Superadmin/Pub/{id_p}/edit', name: 'app_Super_admin_edit', methods: ['GET', 'POST'])]
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

    #[Route('/Superadmin/Pub/{id_p}', name: 'app_Super_admin_delete', methods: ['POST'])]
    public function deletepub(Request $request, Publication $publication, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$publication->getId_p(), $request->request->get('_token'))) {
            $entityManager->remove($publication);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_Super_admin_publication', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/Superadmin/Pub/{id_p}', name: 'app_Super_admin_show', methods: ['GET'])]
    public function showpubid(Publication $publication): Response
    {
        return $this->render('Superadmin/Superadmin_show.html.twig', [
            'publication' => $publication,
        ]);
    }


}
