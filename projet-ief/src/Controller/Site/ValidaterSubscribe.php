<?php

namespace App\Controller\Site;

use App\Controller\Admin\UserCrudController;
use App\Entity\User;
use App\Repository\UserRepository;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\Routing\Annotation\Route;


/**
 * @Route("/admin/user", name="site_admin_user_")
 */

class ValidaterSubscribe extends UserController
{
    private $adminUrlGenerator;

    public function __construct(AdminUrlGenerator $adminUrlGenerator)
    {
        $this->adminUrlGenerator = $adminUrlGenerator;
    }

    /**
     * lien de validation d'inscription, accessible aux admins
     * @Route("/valider-nouvelle-inscription/{id}", name="validate_subscription", methods={"GET"})
     * @param int $id
     */
    public function validateSubscription(int $id, UserRepository $userRepository) 
    {
        $connectedUser=$this->getUser();
        $this->denyAccessUnlessGranted('VALIDATE', $connectedUser);
        $requestingUser = $userRepository->findOneBy(["id" => $id]);

        //Vérification si la demande a été modérée ou non
        if($this->demandAlreadyModerate($requestingUser) === false){
            return $this->render('site/user/validation.html.twig', [
                'admin' => $connectedUser,
                'title' => 'Validation de ',
                'user' => $requestingUser,
            ]);
        }
        return $this->redirectToRoute('site_home_index');
    }

    /**
     * validation d'une demande, passage de USER en MEMBER
     * @Route("/validate-user/{id}", name="validate", methods={"POST"})
     * @param int $id
     */
    public function validateUser(User $requestingUser = null)
    {
        //Vérification si la demande a été modérée ou non
        //dans le cas ou la demande d'inscription aurait déjà été modérée, le token serait vide, 2 possibilité: 
        //-soit la demande a été accepté => compte validé => role modifié; 
        //-soit la demande a été refusée=> compte supprimé.

        if($this->demandAlreadyModerate($requestingUser) === false){    
            //donc si la demande n'a pas été modérée il faut:
            //-vider le token
            //-changer le role de l'utilisateur en ROLE_MEMBER
            $requestingUser->setRoles(['ROLE_MEMBER']);
            $requestingUser->setToken(null);
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('success', 'La demande a été acceptée, l\'utilisateur aura maintenant accès au site, retrouve <a href="' . $this->generateUrlListUsers() . '">ici</a> la liste des utilisateurs');
            
            // TODO:envoie email de confirmation à l'utilisateur
        }
       //redirection vers la page d'accueil.
        return $this->redirectToRoute('site_home_index');
    }

    /**
     * Vérification si la demande a été modérée ou non
     */
    private function demandAlreadyModerate($requestingUser)
    {
        if ($requestingUser === null) {
            $this->addFlash('warning', 'Utilisateur non trouvé. Peut-être a-t-il déjà été refusé, retrouve <a href="%s">ici</a> la liste des utilisateurs', $this->generateUrlListUsers());
            return;
        }elseif($requestingUser->getToken() === null){
            if (in_array('ROLE_MEMBER', $requestingUser->getRoles())) {
                $this->addFlash(
                    'success',
                    'Demande déjà modérée, l\'utilisateur a été accepté, retrouve <a href="' .  $this->generateUrlListUsers()  . '">ici</a> la liste des utilisateurs'
                );
            }else{
                $this->addFlash('warning', 'Utilisateur non trouvé. Peut-être a-t-il déjà été refusé, retrouve <a href="%s">ici</a> la liste des utilisateurs', $this->generateUrlListUsers());
                return;
            }
        }else{
            return false;
        }
    }

    /**
     * création url vers liste utilisateurs pour mettre en msg falsh
     */
    private function generateUrlListUsers()
    {
        return $urlAdminUsers = $this->adminUrlGenerator
                ->setController(UserCrudController::class)
                ->setAction(Action::INDEX)
                ->generateUrl();
    }
}