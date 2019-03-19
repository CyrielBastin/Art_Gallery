<?php

namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class RedirectController extends AbstractController
{

    /**
     * @Route("/redirect-to-painting-view-one-{painting_id}", name="redirect_from_painting_view_one")
     */
    public function redirectFromPaintingViewOne($painting_id)
    {
        return $this->redirectToRoute('painting_view_one', ['id' => $painting_id]);
    }

    /**
     * @Route("/redirect-to-newsletter-view-all", name="redirect_from_newsletter_create_one")
     */
    public function redirectFromNewsletterCreateOne()
    {
        return $this->redirectToRoute('newsletter_view_all');
    }

}