<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/user/profile", name="user_profile_")
 */
class UserProfileController extends AbstractController
{
    /**
     * @Route("/{pseudo}", name="view_one")
     */
    public function userProfile()
    {
        return $this->render('user_profile/user_profile.html.twig');
    }
}
