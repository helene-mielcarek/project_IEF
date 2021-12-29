<?php

namespace App\Controller\Site;

use App\Repository\CategoryRepository;
use App\Repository\EventRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    public function __construct(
        CategoryRepository $categoryRepository, 
        EventRepository $eventRepository,
        UserRepository $userRepository)
    {
        $this->categoryRepository = $categoryRepository;
        $this->eventRepository = $eventRepository;
        $this->userRepository = $userRepository;
    }
    /**
     * @Route("/site/main", name="site_main")
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
