<?php

namespace App\Controller;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use App\Entity\Page;
use App\Form\PageType;
use App\Repository\PageRepository;
use App\Entity\Publication;
use App\Entity\Utilisateur;
use App\Form\PublicationType;
use App\Repository\PublicationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

#[Route('/Admin')]
class AdminController extends AbstractController
{
    #[Route('/Accueil', name: 'app_admin')]
    public function index(): Response
    {
        return $this->render('Admin/index.html.twig');
    }

  /*  #[Route('/publication', name: 'app_publication_index', methods: ['GET'])]
    public function indexx(PublicationRepository $publicationRepository): Response
    {
        return $this->render('Admin/pub_index.html.twig', [
            'publications' => $publicationRepository->findAll(),
        ]);
    }
*/
#[Route('/publication', name: 'app_publication_index', methods: ['GET'])]
public function indexx(Request $request, PublicationRepository $publicationRepository): Response
{
    $searchTerm = $request->query->get('q');
    $publications = $publicationRepository->searchByNom($searchTerm);

    return $this->render('Admin/pub_index.html.twig', [
        'publications' => $publications,
    ]);
}

    #[Route('/publication/new/{idpp}', name: 'app_publication_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager , $idpp): Response

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
            return $this->redirectToRoute('app_publication_admin_new');
        }

        // Set the image property in the entity to the relative path of the uploaded file
        $publication->setImage('assets/img/'.$newFilename);
    }
    $idpp = 289; 
    $page = $entityManager->getReference(Page::class, $idpp); // Debugging statement to check if the page is fetched correctly
    $publication->setPageRelation($page);
            $entityManager->persist($publication);
            $entityManager->flush();

            return $this->redirectToRoute('app_publication_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('Admin/pub_new.html.twig', [
            'publication' => $publication,
            'form' => $form,
        ]);
    }

    #[Route('/publication/{id_p}', name: 'app_publication_show', methods: ['GET'])]
    public function show(Publication $publication): Response
    {
        return $this->render('Admin/pub_show.html.twig', [
            'publication' => $publication,
        ]);
    }

    #[Route('/publication/{id_p}/edit', name: 'app_publication_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Publication $publication, EntityManagerInterface $entityManager): Response
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
                    return $this->redirectToRoute('app_publication_edit');
                }
    
                // Set the image property in the entity to the relative path of the uploaded file
                $publication->setImage('assets/img/'.$newFilename);
            }
            $entityManager->flush();

            return $this->redirectToRoute('app_publication_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('Admin/pub_edit.html.twig', [
            'publication' => $publication,
            'form' => $form,
        ]);
    }

    #[Route('/publication/{id_p}', name: 'app_publication_delete', methods: ['POST'])]
    public function delete(Request $request, Publication $publication, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$publication->getId_p(), $request->request->get('_token'))) {
            $entityManager->remove($publication);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_publication_index', [], Response::HTTP_SEE_OTHER);
    }
/*
    #[Route('/page', name: 'app_page_index', methods: ['GET'])]
    public function indexxx(EntityManagerInterface $entityManager): Response
    {
        $pages = $entityManager
            ->getRepository(Page::class)
            ->findAll();

        return $this->render('Admin/page_index.html.twig', [
            'pages' => $pages,
        ]);
    } */
    #[Route('/page', name: 'app_page_index', methods: ['GET'])]
    public function indexxx(Request $request, PageRepository $pageRepository): Response
{
    $searchTerm = $request->query->get('q');
    $pages = $pageRepository->searchByNom($searchTerm);

    return $this->render('Admin/page_index.html.twig', [
        'pages' => $pages,
    ]);
}
    #[Route('/page/new/{idu}', name: 'app_page_admin_new', methods: ['GET', 'POST'])]
public function newpage(Request $request, EntityManagerInterface $entityManager , $idu): Response
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
            return $this->redirectToRoute('app_page_admin_new');
        }

        // Set the image property in the entity to the relative path of the uploaded file
        $page->setImage('assets/img/'.$newFilename);
    }
   /* $imageFile = $form->get('image')->getData();
if ($imageFile) {
    $newFilename = uniqid().'.'.$imageFile->guessExtension();
    try {
        $imageFile->move(
            $this->getParameter('images_directory'),
            $newFilename
        );
        $page->setImage($newFilename); // Set image property to the filename
    } catch (FileException $e) {
        // Handle file upload exception
        $this->addFlash('error', 'An error occurred while uploading the image.');
        return $this->redirectToRoute('app_page_admin_new');
    }
}*/
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
                    return $this->redirectToRoute('app_page_admin_new');
                }
    
                // Set the logo property in the entity to the relative path of the uploaded file
                $page->setLogo('assets/img/'.$newFilename);
            }
            $idu = 47; 
            $utilisateur = $entityManager->getReference(Utilisateur::class, $idu); // Debugging statement to check if the page is fetched correctly
            $page->setUserRelation($utilisateur);
$entityManager->persist($page);
        $entityManager->flush();

        return $this->redirectToRoute('app_page_index', [], Response::HTTP_SEE_OTHER);
    }


return $this->renderForm('Admin/page_new.html.twig', [
'page' => $page,
    'form' => $form,
]);
}
    #[Route('/page/{idp}', name: 'app_page_admin_show', methods: ['GET'])]
    public function show_admin_page(Page $page): Response
    {
        return $this->render('Admin/page_show.html.twig', [
            'page' => $page,
        ]);
    }
    
    #[Route('/{idp}', name: 'app_page_admin_show_id', methods: ['GET'])]
    public function show_page(EntityManagerInterface $entityManager, $idp = 332): Response
    {
        // Fetch the Page entity by ID
        $page = $entityManager
            ->getRepository(Page::class)
            ->find($idp);

        // Check if the page exists
        if (!$page) {
            throw $this->createNotFoundException('Page not found with id ' . $idp);
        }

        // Render the template with the fetched page
        return $this->render('Admin/page_show.html.twig', [
            'page' => $page,
        ]);
    }
/*    #[Route('/page/new', name: 'app_page_admin_new', methods: ['GET', 'POST'])]
    public function newpage(Request $request, EntityManagerInterface $entityManager): Response
    {
    $page = new Page();
    $form = $this->createForm(PageType::class, $page);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        // Handle form submission and entity persistence
        // Redirect to the appropriate route after successful submission
    }

    return $this->render('Admin/page_new.html.twig', [
        'form' => $form->createView(),
    ]);
}*/


    #[Route('/page/{idp}/edit', name: 'app_page_admin_edit', methods: ['GET', 'POST'])]
    public function edit_page_admin(Request $request, Page $page, EntityManagerInterface $entityManager): Response
    {
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
                    return $this->redirectToRoute('app_page_admin_edit');
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
                    return $this->redirectToRoute('app_page_admin_edit');
                }
    
                // Set the logo property in the entity to the relative path of the uploaded file
                $page->setLogo('assets/img/'.$newFilename);
            }
            $entityManager->flush();

            return $this->redirectToRoute('app_page_admin_show_id', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('Admin/page_edit.html.twig', [
            'page' => $page,
            'form' => $form,
            'errors' => $form->getErrors(true, false), // Récupérer les erreurs de validation du formulaire

        ]);
    }
    #[Route('/page/{idp}/delete', name: 'app_page_admin_delete', methods: ['POST'])]
    public function delete_admin(Request $request, Page $page, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$page->getIdp(), $request->request->get('_token'))) {
            $entityManager->remove($page);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_page_admin_show', [], Response::HTTP_SEE_OTHER);
    }
    
}