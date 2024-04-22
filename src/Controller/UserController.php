<?php

namespace App\Controller;
use App\Entity\Page;
use App\Form\PageType;
use App\Repository\PageRepository;
use App\Entity\Publication;
use App\Form\PublicationType;
use App\Repository\PublicationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

#[Route('/User')]
class UserController extends AbstractController
{

             /********** Page  **********/

    #[Route('/Accueil', name: 'app_index', methods: ['GET'])]
    public function index(PageRepository $pageRepository): Response
    {
        return $this->render('User/index.html.twig', [
            'pages' => $pageRepository->findAll(),
        ]);
    }


    #[Route('/page', name: 'app_page_index_user', methods: ['GET'])]
    public function indexx(Request $request, PageRepository $pageRepository): Response
    {
        $searchTerm = $request->query->get('q');
        $pages = $pageRepository->searchByNom($searchTerm);
    
        return $this->render('User/index_page.html.twig', [
            'pages' => $pages,
        ]);
    }
    
    
    #[Route('/page/{idP}', name: 'app_page_show_user', methods: ['GET'])]
    public function show(Page $page): Response
    {
        return $this->render('User/show_user.html.twig', [
            'page' => $page,
        ]);
    }

                 /********** Publication  **********/


    #[Route('/publication', name: 'app_publication_index_user', methods: ['GET'])]
    public function indexxx(Request $request, PublicationRepository $publicationRepository): Response
    {
        $searchTerm = $request->query->get('q');
        $publications = $publicationRepository->searchByNom($searchTerm);
    
        return $this->render('User/index_publication.html.twig', [
            'publications' => $publications,
        ]);
    }
    
    #[Route('/publication/{id_P}', name: 'app_publication_show_user', methods: ['GET'])]
    public function showw(publication $publication): Response
    {
        return $this->render('User/show_user_pub.html.twig', [
            'publication' => $publication,
        ]);
    }
}
