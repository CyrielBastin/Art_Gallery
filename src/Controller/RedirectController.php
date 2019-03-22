<?php

namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class RedirectController extends AbstractController
{

    /**
     * @Route("/change-locale-to-en", name="change_locale_to_en")
     */
    public function changeLocaleToEn()
    {
        setcookie('_locale', 'en', time() + 3600*24*3, null, null, false, true);

        return $this->redirectToRoute('homepage', ['_locale' => 'en']);
    }

    /**
     * @Route("/change-locale-to-fr", name="change_locale_to_fr")
     */
    public function changeLocaleToFr()
    {
        setcookie('_locale', 'fr', time() + 3600*24*3, null, null, false, true);

        return $this->redirectToRoute('homepage', ['_locale' => 'fr']);
    }

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