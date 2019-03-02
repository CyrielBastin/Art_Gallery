<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;


class HomePageController extends AbstractController
{
    /**
     * @Route("/", name="homepage")
     */
    public function index()
    {
        return $this->render('home_page/index.html.twig');
    }

    /**
     * @Route("/general-terms-and-conditions.html", name="terms_and_conditions")
     */
    public function termsAndConditions(){
        return $this->render('home_page/general_terms_and_conditions.html.twig');
    }

    /**
     * @Route("/privacy-policy", name="privacy_policy")
     */
    public function privacyPolicy(){
        return $this->render('home_page/privacy_policy.html.twig');
    }

    /**
     * @Route("/cookie-policy", name="cookie_policy")
     */
    public function cookiePolicy(){
        return $this->render('home_page/cookie_policy.html.twig');
    }
}
