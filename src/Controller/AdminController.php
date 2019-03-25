<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/{_locale}/admin", name="admin_", requirements={"_locale"="%app.locales%"})
 */
class AdminController extends AbstractController
{

    /**
     * @Route("/dashboard", name="dashboard")
     */
    public function viewDashboard()
    {
        return $this->render('admin/dashboard.html.twig');
    }

}
