<?php

namespace App\Service;

use App\Repository\UserRepository;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;

class Mailer {
    /**
     * @var MailerInterface
     */
    private $mailer;

    public function __construct(MailerInterface $mailer, UserRepository $userRepository)
    {
        $this->mailer = $mailer;
        $this->userRepository = $userRepository;
    }
    /**
     * Envoi mail aux admins lors d'une inscription.
     *
     * @param string $email
     * @param User $user
     * 
     */
    public function sendMailSubRequest($email, $user) 
    {        
        $email = (new TemplatedEmail())
            ->from('register@example.com')
            ->to(new Address($email))
            ->subject('Nouvelle demande d\'inscription')

            // path of the Twig template to render
            ->htmlTemplate('mailer/inscription.html.twig')

            // pass variables (name => value) to the template
            ->context([
                'user' => $user,
            ])
        ;
            $this->mailer->send($email);
    }

    /**
     * Envoi mail aux utilisateur lors de leur inscription ou de la validation de leur inscription
     *
     * @param string $email
     * @param User $user
     * @param boolean $validate
     * @return void
     */
    public function sendMailUserSub($email, $user, $validate)
    {
        $email = (new TemplatedEmail())
        ->from('register@example.com')
        ->to(new Address($email));

        if($validate === false){

            $email->subject('Demande d\'inscription');
        }else{
            $email->subject('Demande d\'inscription a été acceptée');
        }
        // path of the Twig template to render
        $email->htmlTemplate('mailer/for_user.html.twig')

        // pass variables (name => value) to the template
        ->context([
            'user' => $user,
            'validate' => $validate,
        ])
    ;
        $this->mailer->send($email);

    }

    /**
     * Envoi mail à l'auteur de l'événement qui est maintenant complet 
     * ou qui peut ne plus être complet, après la participation ou 
     * l'annulation d'une participation
     *
     * @param string $email
     * @param Event $event
     * @param User $user
     * @param boolean $participation
     * @return void
     */
    public function sendMailAuthorEvent($email, $event, $user, $participation)
    {
        $email = (new TemplatedEmail())
        ->from('register@example.com')
        ->to(new Address($email));
        if($participation === true ){
            $email->subject('Votre événement peut être ouvert aux participant.')
            // path of the Twig template to render
            ->htmlTemplate('mailer/event_status.html.twig');
        }else{
            $email->subject('Votre événement est maintenant complet.')
            ->htmlTemplate('mailer/event_status.html.twig');
        }
            // pass variables (name => value) to the template
            $email->context([
                'event' => $event,
                'user' => $user,
                'participation' => $participation,
                ])
                ;
                $this->mailer->send($email);

    }
}