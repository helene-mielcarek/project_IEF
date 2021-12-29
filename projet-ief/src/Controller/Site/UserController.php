<?php

namespace App\Controller\Site;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

    /**
     * @Route("/user", name="site_user_")
     */
class UserController extends AbstractController
{
    /**
     * @Route("/{id}", name="read", requirements={"id" : "\d+"}, methods={"GET"})
     */
    public function read(int $id, User $user): Response
    {
        // dd($user);
        return $this->render('site/user/read.html.twig', [
            'user' => $user,
        ]);
    }

    /**
     * @Route("/add", name="add", methods={"GET","POST"})
     */
    public function add(Request $request): Response
    {
        $user = new User();
        $this->denyAccessUnlessGranted('ADD', $user);
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form-> isValid()) {
        }

        return $this->render('site/user/add.html.twig', [
            'form' => $form->createView(),
            'title' => 'Formulaire de demande d\'ajout',
            'info' => 'Envoyer la demande',
        ]);
    }


    /**
     * @Route("/edit/{id}", name="edit", requirements={"id" : "\d+"}, methods={"GET","POST"})
     */
    public function edit(int $id, Request $request, User $user): Response
    {
        $this->denyAccessUnlessGranted('EDIT', $user);
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form-> isValid()) {
        }

        return $this->render('site/user/add.html.twig', [
            'form' => $form->createView(),
            'title' => 'Modification de '. $user->getName(),
            'info' => 'Modifier mes informations',
        ]);

    }
}
