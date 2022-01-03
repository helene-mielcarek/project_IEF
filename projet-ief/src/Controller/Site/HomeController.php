<?php

namespace App\Controller\Site;

use App\Repository\EventRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/home", name="site_home_")
 */
class HomeController extends MainController
{
    /**
     * @Route("", name="index")
     */
    public function home(EventRepository $eventRepository): Response
    {
        //on a besoin:
            // 1: des 5 derniers events enregistrés
            // 2: des 5 prochains events où l'user est participant
            // 3: des 5 derniers events où l'user était participant
            // 4: de la liste des category
        $user = $this->getUser();
        //    dd($user->getId());
            //1:
        $lastFiveEvents = $eventRepository->findLastFiveForHome();

            //2: 
        $lastFiveEventsParticipant = $eventRepository->findLastFiveParticipant($user->getId());
        //3:
        $nextFiveEventsParticipant =$eventRepository->findNextFiveParticipant($user->getId());
        // dump($nextFiveEventsParticipant, $lastFiveEventsParticipant);
        // dd($lastFiveEventsParticipant);
            //4:
        // $categories = $this->findAllCategory();
        // dd($lastFiveEvents);
        return $this->render('site/home/index.html.twig', [
            // 'categories' => $categories,
            'lastFiveEvents' => $lastFiveEvents,
            'titre' => 'IEF Pays de Lorient',
            'lastFiveEventsParticipant' => $lastFiveEventsParticipant,
            'nextFiveEventsParticipant' => $nextFiveEventsParticipant,
        ]);
    }
}
