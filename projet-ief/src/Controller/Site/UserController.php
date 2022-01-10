<?php

namespace App\Controller\Site;

use App\Entity\User;
use App\Form\UserType;
use App\Form\UserTypeEdit;
use App\Repository\UserRepository;
use App\Service\Mailer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
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
    public function add(Request $request, UserPasswordHasherInterface $userPasswordHasher, Mailer $mailer, UserRepository $userRepository): Response
    {
        $user = new User();
        //accès pour les utilisateurs non conecté, donc pas de voter sinon ils sont redirigés vers app_login car non autorisés par le voter
        // $this->denyAccessUnlessGranted('ADD', $user);
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // vérification de l'email unique dans le formulaire dans l'entité
            //ajout de la date de création
            $user->setCreatedAt(new \DateTime('now'));
            //verif password non vide ou null
            if ($user->getPassword()){
                //hashage password
                $user->setPassword($userPasswordHasher->hashPassword($user,$user->getPassword()));
                // dd($user);
            }
            else {
                $this->addFlash('warning', 'il n\'y a pas de mot de passe');
            }
            $user->setRoles(['ROLE_USER']);
            $user->setToken($this->generateToken());
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();
            
            //Enovie d'un mail aux admins pour validation du compte
            //utilisation de mailer
                //récupération des utilisateurs administrateurs
                $admins = $userRepository->findAdmins('["ROLE_ADMIN"]');

            //Boucle pour envoyer un mail à chaque admin
            foreach ($admins as $admin) {
                $mailer->sendMailSubRequest($admin->getEmail(), $user);
            }

            //envoie mail de la demande à l'utilisateur
            $mailer->sendMailUserSub($user->getEmail(), $user, false);


            $this->addFlash('success', 'Votre demande a été envoyée, attendez sa validation par un admin, merci.');

            return $this->redirectToRoute('site_main_index');

        }

        return $this->render('site/user/add.html.twig', [
            'form' => $form->createView(),
            'title' => 'Demande d\'ajout',
            'info' => 'Envoyer la demande',
        ]);
    }


    /**
     * @Route("/edit/{id}", name="edit", requirements={"id" : "\d+"}, methods={"GET","POST"})
     */
    public function edit(int $id, Request $request, User $user): Response
    {
        $this->denyAccessUnlessGranted('EDIT', $user);
        $form = $this->createForm(UserTypeEdit::class, $user);
        $password = $user->getPassword();
        // dump($user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if($user->getPassword() == 'no modificated'){
                $user->setPassword($password);
            }
            $user->setUpdatedAt(new \DateTime('now'));
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('success', 'Vos informations ont été modifiées.');

            return $this->redirectToRoute('site_user_read', ['id' => $id]);
        }

        return $this->render('site/user/add.html.twig', [
            'form' => $form->createView(),
            'title' => 'Modification de '. $user->getName(),
            'info' => 'Modifier mes informations',
        ]);

    }

    /**
     *@return string
     *@throws \Exception
     */
    private function generateToken()
    {
        return rtrim(strtr(base64_encode(random_bytes(32)), '+/','-_'), '=');
    }

    /**
     * @Route("/delete/{id}", name="delete", methods={"DELETE"})
     */
    public function delete(int $id, User $user, UserRepository $userRepository): Response
    {
        $this->denyAccessUnlessGranted('DELETE', $user);
        // dd($user);

        // if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token'))) {
            // dd($user);
            $entityManager = $this->getDoctrine()->getManager();
            //si c'est l'utilisateur qui supprime son compte
            if($this->getUser() == $userRepository->find($id)){
                $this->container->get('security.token_storage')->setToken(null);
                $entityManager->remove($userRepository->find($id));
                $entityManager->flush();

                //suppression session
                // $session = new Session();
                // $session->invalidate();
                $this->addFlash('success', 'Compte utilisateur supprimé.');
                return $this->redirectToRoute('site_main_index');
            }else{
                $entityManager->remove($userRepository->find($id));
                $entityManager->flush();

                $this->addFlash('success', 'La demande et le compte utilisateur ont été supprimés.');
                return $this->redirectToRoute('site_home_index', [], Response::HTTP_SEE_OTHER);
            }
        // }
    }
}
