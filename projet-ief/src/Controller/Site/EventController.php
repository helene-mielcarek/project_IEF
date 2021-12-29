<?php

namespace App\Controller\Site;

use App\Data\SearchData;
use App\Entity\Event;
use App\Entity\Participant;
use App\Entity\User;
use App\Form\EventType;
use App\Form\SearchType;
use App\Repository\EventRepository;
use App\Repository\UserRepository;
use App\Service\ImageUploader;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/event", name="site_event_")
*/
class EventController extends MainController
{
    /**
     * @Route("/", name="browse", methods={"GET"} )
     */
    public function browse(Request $request, EventRepository $eventRepository): Response
    {
        //Pour la pagination
        //Pour le filtre et la recherche
        $data = new SearchData();
        $data->page = (int)$request->get('page',1);
        $form = $this->createForm(SearchType::class, $data);
        $form->handleRequest($request);

        $events = $eventRepository->findAllEventPaginationWithSearch($data);

        $events->setCustomParameters([
            'align' => 'center',
        ]);

        $events->setSortableTemplate('site/tri.html.twig');
        if($request->get('ajax')){
            return new JsonResponse([
                'content' => $this->renderView('site/event/_content.html.twig', ['events' => $events]),
                'pagination' => $this->renderView('site/event/_pagination.html.twig', ['events' => $events]),
                'sorting' => $this->renderView('site/event/_sorting.html.twig', ['events' => $events]),
                'pages' => ceil($events->getTotalItemCount() / $events->getItemNumberPerPage()),
            ]);
        }
        return $this->render('site/event/browse.html.twig', [
            'events' => $events,
            'form' => $form->createView(),
            'titre' => 'Les évenements',
            'titrePage' => 'Retrouvez ici tous les événements'
        ]);
    }

    /**
     * @Route("/{id}", name="read", requirements={"id" : "\d+"}, methods={"GET","POST"} )
     */
    public function read(int $id, Request $request, Event $event): Response
    {
        $form = $this->createForm(EventType::class, $event);
        
        $form->handleRequest($request);
        
        if($form->isSubmitted() && $form->isValid()) {
            //redirect pour ajout/modif en bdd, seulement si on utilise un nb de participants, plutot que juste les familles participantes
            // $numberParticipants = $form->get('nbParticipants')->getData();
            // return $this->redirectToRoute('site_event_add_participant_nb', ['id' => $event->getId(), 'nb' => $numberParticipants]);

            //redirect pour ajout/modif en bdd, si on utilise les familles participantes
            return $this->redirectToRoute('site_event_add_participant', ['id' => $event->getId()]);
        }
        return $this->render('site/event/read.html.twig', [
            // 'categories' => $categories,
            'event' => $event,
            'form' => $form->createView(),
            'title' => $event->getTitle(),
        ]);
    }

    /**
     * Ajout en tant que participant si on utilise un nombre de participant(enfant)
     * @Route("/{id}/participation/{nb}", name="add_participant_nb", requirements={"id" : "\d+", "nb" : "\d+"}, methods={"GET"} )
     */
    public function addParticipantWithNb(int $id, int $nb, Event $event){
        // //Vérification des droits du User
        // $this->denyAccessUnlessGranted('PARTICIPE', $event);

        // //modification seulement si event non complet
        // if($event->getComplet() == false){
        //     foreach ($event->getParticipants()->toArray() as $participation) {
        //     //si partcipant deja enregistré, c'est un modification
        //         if (in_array($this->getUser(), $participation->getUser()->toArray())) {
        //             //TODO: faire la modification du nb de participants
        //             //Possibilité de modifier le nb et possibilté de se supprimer
        //             $this->addFlash('success', 'Ok, on enregistre!');
        //             return $this->redirectToRoute('site_event_read', ['id' => $event->getId()]);
        //         //Si pas deja participant, c'est un ajout
        //         } else {
        //             $nbParticipants = 0;
        //             foreach ($event->getParticipants()->toArray() as $participation){
        //                 $nbParticipants = $nbParticipants + $participation->getNumber();
        //             }
        //             $newNbPart = $nbParticipants + $nb;
        //             //si nb <= limite && < limite+2 ==> ajout nb et user
        //             if ($newNbPart <= $event->getLimite() && $newNbPart < ($event->getLimite() + 2) || $event->getLimite() == 0) {
        //                 //si partcipant deja enregistré
        //                 if (in_array($this->getUser(), $participation->getUser()->toArray())) {
        //                     //message error
        //                     $this->addFlash('warning', 'Tu es déjà enregistré.');
        //                     return $this->redirectToRoute('site_event_read', ['id' => $event->getId()]);
        //                 } else {
        //                     $participant = new Participant();
        //                     $participant->addEvent($event);
        //                     //ajout nbParticipant
        //                     $participant->setNumber($nb);
        //                     // $event->setNbParticipants($nbParticipants + $nb);
        //                     //ajout User en FamParticipant
        //                     $participant->addUser($this->getUser());
        //                     // $event->addFamParticipant($this->getUser());
        //                     $entityManager = $this->getDoctrine()->getManager();
        //                     // dd($participant);
        //                     $entityManager->persist($participant);
        //                     $entityManager->flush();
                
        //                 }
        //                 //si nb = limite
        //                 if ($nbParticipants === $event->getLimite() && $event->getLimite() != 0) {
        //                     //event complet
        //                     $event->setComplet(true);
        //                 }
                    
        //                 // dd($nb);
        //                 //ajout en bdd
        //                 $this->getDoctrine()->getManager()->flush();
        //                 $this->addFlash('success', 'Parfait! Tu es enregistré en tant que participant!');

        //                 return $this->redirectToRoute('site_event_read', ['id' => $event->getId()]);
        //             }
        //             //else ==> message erreur (a afficher en twig)
        //             else {
        //                 $this->addFlash('warning', 'Cet évenement est déjà complet.');
        //                 return $this->redirectToRoute('site_event_read', ['id' => $event->getId()]);
        //             }
        //         }
        //     }
        // }
    }

    /**
     * Ajout en tant que participant si on utilise un nombre de familles participantes(USER)
     * @Route("/{id}/participation", name="add_participant", requirements={"id" : "\d+", "nb" : "\d+"}, methods={"GET"} )
     */
    public function addParticipant(int $id, Event $event) {
        //vérification avec le voter, des droits de l'utilisateur
        $this->denyAccessUnlessGranted('PARTICIPE', $event);
        
        //modification seulement si event non complet
        if($event->getComplet() == false){
            //récupérer nb de famille participantes
            $nbFamParticipants = count($event->getFamParticipants()->toArray());
            //recherche si User est en participant dans l'event
            // dd($event->getFamParticipants()->toArray());
            foreach ($event->getFamParticipants()->toArray() as $participation) {
                //si partcipant deja enregistré, c'est un modification
                if ($this->getUser() === $participation) {
                    //suppression du User des participants
                    // dd('déja enregistré');
                    $event->removeFamParticipant($this->getUser());
                    //retirer 1 au nb de participant
                    $newNbFamParticipants = $nbFamParticipants - 1;
                    if ($newNbFamParticipants < $event->getLimite() && $event->getLimite() != 0) {
                        //event complet
                        $event->setComplet(false);
                    }
                    //ajout en bdd
                    $this->getDoctrine()->getManager()->flush();
                    $this->addFlash('success', 'Ok, on enregistre!');
                    return $this->redirectToRoute('site_event_read', ['id' => $event->getId()]);

                //Si pas deja participant, c'est un ajout
                } else {
                    //ajouter le User au nb de famille
                    $newNbFamParticipants = 1 + $nbFamParticipants;
                    //si newNbFamParticipants <= limite
                    if ($newNbFamParticipants <= $event->getLimite()) {
                        //si partcipant deja enregistré
                        if (in_array($this->getUser(), $event->getFamParticipants()->toArray())) {
                            //message error
                            $this->addFlash('warning', 'Tu es déjà enregistré.');
                            return $this->redirectToRoute('site_event_read', ['id' => $event->getId()]);
                        } 
                        //Si pas enregistré
                        else {
                            //ajout de la famille participante(User)
                            $event->addFamParticipant($this->getUser());
                        }
                        //si newNbFamParticipants = limite alors event = complet
                        if ($newNbFamParticipants === $event->getLimite() && $event->getLimite() != 0) {
                            //event complet
                            $event->setComplet(true);
                        }
                        //ajout en bdd
                        $this->getDoctrine()->getManager()->flush();
                        $this->addFlash('success', 'Parfait! Tu es enregistré en tant que participant!');

                        return $this->redirectToRoute('site_event_read', ['id' => $event->getId()]);
                    }
                }
            }
        }
        //si event complet message error et redirect
        else {
            $this->addFlash('warning', 'Cet évenement est déjà complet.');
            return $this->redirectToRoute('site_event_read', ['id' => $event->getId()]);
        }

    }


    /**
     * @Route("/add", name="add", methods={"GET", "POST"} )
     */
    public function add(Request $request, ImageUploader $imageUploader): Response
    {
        
        $event = new Event();
        $this->denyAccessUnlessGranted('ADD', $event);
        $form = $this->createForm(EventType::class, $event);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form-> isValid()) {
            
            // dd($request);
            $event->setCreatedAt(new \DateTime('now'));
            $img = $form->get('img')->getData();
            // dd($img);
            if($img != null){
                $imageUploader->uploadEventImage($event, $img);
            }
            $event->setAuthor($this->getUser());
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($event);
            $entityManager->flush();
            $this->addFlash('success', 'Evénement ajouté');

            // dd($event);

            return $this->redirectToRoute('site_event_read', ['id' => $event->getId()]);
        }

        return $this->render('site/event/add.html.twig', [
            'form' => $form->createView(),
            'title' => 'Ajout d\'un évement',
            'info' => 'Ajouter',
        ]);
    }

    /**
     * @Route("/edit/{id}", name="edit", requirements={"id" : "\d+"}, methods={"GET", "POST"} )
     */
    public function edit(int $id, Request $request, ImageUploader $imageUploader, Event $event): Response
    {
        $this->denyAccessUnlessGranted('VIEW', $event);
        $form = $this->createForm(EventType::class, $event);
        // dd($event);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form-> isValid()) {
            // dd($request);
            $event->setUpdatedAt(new \DateTime('now'));
            $img = $form->get('img')->getData();
            // dd($img);
            if($img != null){
                $imageUploader->uploadEventImage($event, $img);
            }

            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('success', 'Evénement modifié');

            // dd($event);

            return $this->redirectToRoute('site_event_read', ['id' => $id]);
        }

        return $this->render('site/event/add.html.twig', [
            'form' => $form->createView(),
            'title' => 'Modification de '. $event->getTitle(),
            'info' => 'Modifier',
        ]);
    }

    /**
     * @Route("/browse_user/{id}", name="browse_user", requirements={"id" : "\d+"}, methods={"GET"} )
     */
    public function browseUser(int $id, Request $request, EventRepository $eventRepository, UserRepository $userRepository): Response
    {
        $user = $userRepository->find($id);
        $this->denyAccessUnlessGranted('VIEW', $user);
        //Pour la pagination
        //Pour le filtre et la recherche
        $data = new SearchData();
        $data->page = (int)$request->get('page',1);
        $form = $this->createForm(SearchType::class, $data);
        $form->handleRequest($request);
        //besoin de :
         //1: tous les évents de l'utilisateur connecté
        $events = $eventRepository->findAllEventForUserPaginationWithSearch($data, $id);
        dump($events);
        $events->setCustomParameters([
            'align' => 'center',
        ]);

        $events->setSortableTemplate('site/tri.html.twig');
        // dd($events);
        if($request->get('ajax')){
            // dd('ok');
            return new JsonResponse([
                'content' => $this->renderView('site/event/_content.html.twig', ['events' => $events]),
                'pagination' => $this->renderView('site/event/_pagination.html.twig', ['events' => $events]),
                'sorting' => $this->renderView('site/event/_sorting.html.twig', ['events' => $events]),
                'pages' => ceil($events->getTotalItemCount() / $events->getItemNumberPerPage()),
            ]);
        }
        return $this->render('site/event/browse.html.twig', [
            // 'categories' => $categories,
            'events' => $events,
            'form' => $form->createView(),
            'titre' => 'Mes évenements',
            'titrePage' => 'Voici les événements que vous avez créés'
        ]);
    }
    /**
     * @Route("/delete/{id}", name="delete", methods={"DELETE"})
     */
    public function delete(int $id, Request $request, Event $event): Response
    {
        $this->denyAccessUnlessGranted('DELETE', $event);
        // if ($this->isCsrfTokenValid('delete'.$event->getId(), $request->request->get('_token'))) {
            // dd($event);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($event);
            $entityManager->flush();
            $this->addFlash('success', 'Événement supprimé.');
        // }

        return $this->redirectToRoute('site_event_browse', [], Response::HTTP_SEE_OTHER);
    }

}

//TODO route pour agenda "/agenda"
