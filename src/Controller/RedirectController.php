<?php

namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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
     * @Route("/admin/change-locale-to-en", name="admin_change_locale_to_en")
     */
    public function adminChangeLocaleToEn()
    {
        setcookie('_locale', 'en', time() + 3600*24*3, null, null, false, true);

        return $this->redirectToRoute('admin_dashboard', ['_locale' => 'en']);
    }

    /**
     * @Route("admin/change-locale-to-fr", name="admin_change_locale_to_fr")
     */
    public function adminChangeLocaleToFr()
    {
        setcookie('_locale', 'fr', time() + 3600*24*3, null, null, false, true);

        return $this->redirectToRoute('admin_dashboard', ['_locale' => 'fr']);
    }

    /**
     * @Route("/redirect-to-painting-view-one-{painting_id}", name="redirect_from_painting_view_one")
     */
    public function redirectFromPaintingViewOne(Request $request, $painting_id)
    {
        return $this->redirectToRoute('painting_view_one', ['_locale' => $request->getLocale(), 'id' => $painting_id]);
    }

    /**
     * @Route("/redirect-to-newsletter-view-all", name="redirect_from_newsletter_create_one")
     */
    public function redirectFromNewsletterCreateOne(Request $request)
    {
        return $this->redirectToRoute('newsletter_view_all', [ '_locale' => $request->getLocale() ]);
    }

    /**
     * @Route("/admin/redirect-to-newsletter-view-all", name="redirect_from_admin_newsletter_create_one")
     */
    public function redirectFromAdminNewsletterCreateOne(Request $request)
    {
        return $this->redirectToRoute('admin_newsletter_view_all', ['_locale' => $request->getLocale()]);
    }

}
