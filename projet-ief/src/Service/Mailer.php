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
    public function sendMailSubRequest($email, $user) 
    {        
        $email = (new TemplatedEmail())
            ->from('resgister@example.com')
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

}