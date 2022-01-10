<?php

namespace App\Controller\Site;

use App\Repository\EventRepository;
use App\Repository\LibraryImgRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/librairy", name="site_librairy_")
 */
class LibrairyController extends AbstractController
{
    /**
     * Liste librairies pour un event donné
     * @Route("/browse/event_{id}", name="browse_for_event", methods="GET")
     */
    public function browse_for_event(int $id, LibraryImgRepository $libraryImgRepository,EventRepository $eventRepository): Response
    {
        $event =$eventRepository->find($id);
        $librairies = $libraryImgRepository->findBy(['relatedEvent'=>$id]);
        return $this->render('site/librairy/index.html.twig', [
            'librairies' => $librairies,
            'event' => $event,
        ]);
    }
}

//TODO: route pour afficher une librairy en fonction de l'event
