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
     * @Route("/", name="indexpage")
     */
    public function index(Request $request)
    {
        if($request->cookies->has('_locale')){
            setcookie('_locale', $request->cookies->get('_locale'), time() + 3600*24*3, null, null, false, true);
            return $this->redirectToRoute('homepage', ['_locale' => $request->cookies->get('_locale')]);
        }else{
            setcookie('_locale', 'en', time() + 3600*24*3, null, null, false, true);
        }

        return $this->redirectToRoute('homepage', ['_locale' => 'en']);
    }


    /**
     * @Route("/{_locale}/", name="homepage", requirements={"_locale"="%app.locales%"})
     */
    public function homePage()
    {
        return $this->render('home_page/index.html.twig');
    }


    /**
     * @Route("/{_locale}/contact-us", name="contact_us", requirements={"_locale"="%app.locales%"})
     */
    public function contactUs(Request $request, ContactService $contactService)
    {
        $contact = new Contact();
        $form = $this->createForm(ContactType::class, $contact);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $contactService->contactNotify($contact);
            return $this->redirectToRoute('homepage', ['_locale' => $request->getLocale()]);
        }

        return $this->render('home_page/contact.html.twig', ['form' => $form->createView()]);
    }

    // ===========================================================================================
    // Routes for the FOOTER
    // ===========================================================================================

    /**
     * @Route("/{_locale}/general-terms-and-conditions.html", name="terms_and_conditions", requirements={"_locale"="%app.locales%"})
     */
    public function termsAndConditions(){
        return $this->render('home_page/general_terms_and_conditions.html.twig');
    }

    /**
     * @Route("/{_locale}/privacy-policy.html", name="privacy_policy", requirements={"_locale"="%app.locales%"})
     */
    public function privacyPolicy(){
        return $this->render('home_page/privacy_policy.html.twig');
    }

    /**
     * @Route("/{_locale}/cookie-policy.html", name="cookie_policy", requirements={"_locale"="%app.locales%"})
     */
    public function cookiePolicy(){
        return $this->render('home_page/cookie_policy.html.twig');
    }

}
