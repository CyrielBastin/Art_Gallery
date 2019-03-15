<?php

namespace App\Service;


use App\Entity\NewsletterMessages;
use App\Entity\NewsletterSubscribedPeople;
use Doctrine\ORM\EntityManagerInterface;
use Twig\Environment;

class NewsletterService
{
    /**
     * @var \Swift_Mailer
     */
    private $mailer;
    /**
     * @var Environment
     */
    private $renderer;
    /**
     * @var EntityManagerInterface
     */
    private $em;

    public function __construct(\Swift_Mailer $mailer, Environment $renderer, EntityManagerInterface $em)
    {
        $this->mailer = $mailer;
        $this->renderer = $renderer;
        $this->em = $em;
    }

    public function sendNewsletter(NewsletterMessages $newsletterMessage)
    {
        $newsletter = $this->em->getRepository(NewsletterSubscribedPeople::class)->findAll();

        $recipient = [];
        foreach ($newsletter as $item){
            $recipient[] = $item['email'];
        }

        $message = new \Swift_Message();
        $message->setFrom('info@art_gallery.com')
                ->setTo($recipient)
                ->setSubject($newsletterMessage->getSubject())
                ->setBody($this->renderer->render('emails/newsletter.html.twig', ['newsletter' => $newsletterMessage]), 'text/html')
            ;

        $this->mailer->send($message);
    }
}
