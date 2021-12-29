<?php

namespace App\Controller\Site;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LibrairyController extends AbstractController
{
    /**
     * @Route("/site/librairy", name="site_librairy")
     */
    public function index(): Response
    {
        return $this->render('site/librairy/index.html.twig', [
            'controller_name' => 'LibrairyController',
        ]);
    }
}

//TODO: route pour afficher une librairy en fonction de l'event
