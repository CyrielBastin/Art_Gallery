<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Form\ContactType;
use App\Service\ContactService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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
     * @Route("/contact-us", name="contact_us")
     */
    public function contactUs(Request $request, ContactService $contactService)
    {
        $contact = new Contact();
        $form = $this->createForm(ContactType::class, $contact);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $contactService->contactNotify($contact);
            return $this->redirectToRoute('homepage');
        }

        return $this->render('home_page/contact.html.twig', ['form' => $form->createView()]);
    }

    // ===========================================================================================
    // Routes pour le FOOTER
    // ===========================================================================================

    /**
     * @Route("/general-terms-and-conditions.html", name="terms_and_conditions")
     */
    public function termsAndConditions(){
        return $this->render('home_page/general_terms_and_conditions.html.twig');
    }

    /**
     * @Route("/privacy-policy.html", name="privacy_policy")
     */
    public function privacyPolicy(){
        return $this->render('home_page/privacy_policy.html.twig');
    }

    /**
     * @Route("/cookie-policy.html", name="cookie_policy")
     */
    public function cookiePolicy(){
        return $this->render('home_page/cookie_policy.html.twig');
    }

}
