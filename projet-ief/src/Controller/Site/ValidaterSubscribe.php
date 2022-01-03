<?php

namespace App\Controller\Site;

use App\Controller\Admin\UserCrudController;
use App\Entity\User;
use App\Repository\UserRepository;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;


/**
 * @Route("/admin/user", name="site_admin_user_")
 */

class ValidaterSubscribe extends UserController
{
    /**
     * lien de validation d'inscription, accessible aux admins
     * @Route("/valider-nouvelle-inscription/{token}", name="validate_subscription", methods={"GET"})
     * @param string $token
     */
    public function validateSubscription(string $token, User $user, UserRepository $userRepository, AdminUrlGenerator $adminUrlGenerator) 
    {
        $this->denyAccessUnlessGranted('VALIDATE', $user);
        $requestingUser = $userRepository->findOneBy(["token" => $token]);
        if ($requestingUser){
            return $this->render('site/user/validation.html.twig', [
                'admin' => $user,
                'title' => 'Validation de ',
                'user' => $requestingUser,
            ]);
        } else {
            $urlAdminUsers = $adminUrlGenerator
                ->setController(UserCrudController::class)
                ->setAction(Action::INDEX)
                ->generateUrl();

            $this->addFlash('warning', 'Demande déjà modérée, retrouve <a href="' .$urlAdminUsers .'">ici</a> la liste des utilisateurs');
            throw $this->createNotFoundException('Demande déjà modérée');
            return $this->redirectToRoute('site_home_index');
        }
    }

    /**
     * validation d'une demande, passage de USER en MEMBER
     * @Route("/validate-user/{id}", name="validate", methods={"POST"})
     * @param int $id
     */
    public function validateUser(User $user = null, AdminUrlGenerator $adminUrlGenerator)
    {
        //Vérification si la demande a été modérée ou non
        //dans le cas ou la demande d'inscription aurait déjà été modérée, le token serait vide, 2 possibilité: 
        //-soit la demande a été accepté => compte validé => role modifié; 
        //-soit la demande a été refusée=> compte supprimé.

            //création url vers liste utilisateurs pour mettre en msg falsh
            $urlAdminUsers = $adminUrlGenerator
                ->setController(UserCrudController::class)
                ->setAction(Action::INDEX)
                ->generateUrl();
        if (null === $user || $user->getToken() === null){
            if(in_array('ROLE_MEMBER', $user->getRoles())){
                $this->addFlash('success', 
                'Demande déjà modérée, l\'utilisateur a été accepté, , retrouve <a href="' .  $urlAdminUsers  . '">ici</a> la liste des utilisateurs'
            );
                throw $this->createNotFoundException('Demande déjà accepté');
            }
            throw $this->createNotFoundException('Utilisateur non trouvé.');
            $this->addFlash('warning', 'Utilisateur non trouvé. Peut-être a-t-il déjà été refusé., retrouve <a href="%s">ici</a> la liste des utilisateurs', $urlAdminUsers);            
        } else {
            //donc si la demande n'a pas été modérée il faut:
            //-vider le token
            //-changer le role de l'utilisateur en ROLE_MEMBER
            $user->setRoles(['ROLE_MEMBER']);
            $user->setToken(null);
            // $this->getDoctrine()->getManager()->flush();
            $this->addFlash('success', 'La demande a été acceptée, l\'utilisateur aura maintenant accès au site, retrouve <a href="' . $urlAdminUsers . '">ici</a> la liste des utilisateurs');
            
            // TODO:envoie email de confirmation à l'utilisateur
        }


       //redirection vers la page d'accueil.
        return $this->redirectToRoute('site_home_index');
    }
}