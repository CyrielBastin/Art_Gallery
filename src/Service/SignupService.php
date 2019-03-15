<?php

namespace App\Service;


use App\Entity\User;
use Twig\Environment;

class SignupService
{
    /**
     * @var \Swift_Mailer
     */
    private $mailer;
    /**
     * @var Environment
     */
    private $renderer;

    public function __construct(\Swift_Mailer $mailer, Environment $renderer)
    {
        $this->mailer = $mailer;
        $this->renderer = $renderer;
    }

    public function registrationEmail(User $user)
    {
        $message = new \Swift_Message();
        $message->setFrom('info@art_gallery.com')
            ->setTo($user->getEmail())
            ->setSubject('Account registration')
            ->setBody($this->renderer->render('emails/registration.html.twig', ['account' => $user]), 'text/html')
        ;

        $this->mailer->send($message);
    }
}
