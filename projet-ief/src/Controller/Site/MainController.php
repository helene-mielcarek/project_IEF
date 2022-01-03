<?php

namespace App\Controller\Site;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class MainController extends AbstractController
{
    /**
     * @Route("/", name="site_main_index")
     * page accueil, user non connecté
     */
    public function index(): Response
    {
        return $this->render('site/main/index.html.twig', [
            'controller_name' => 'MainController',
        ]);
    }

    protected function findAllCategory() 
    {
        return $this->categoryRepository->findAll();
    }

    protected function findAllEventPaginationWithSearch($data)
    {
        return $this->eventRepository->findAllEventPaginationWithSearch($data);
    }


    protected function findEventById($id)
    {
        return $this->eventRepository->findByIdForRead($id);
    }
}
