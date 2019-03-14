<?php

namespace App\Service;


use App\Entity\Contact;
use Twig\Environment;

class ContactService
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

    public function contactNotify(Contact $contact)
    {
        $message = (new \Swift_Message())
                    ->setFrom($contact->getEmail())
                    ->setTo('info@art_gallery.com')
                    ->setReplyTo($contact->getEmail())
                    ->setSubject($contact->getSubject())
                    ->setBody($this->renderer->render('emails/contact.html.twig', ['contact' => $contact]), 'text/html')
        ;

        $this->mailer->send($message);

    }
}
