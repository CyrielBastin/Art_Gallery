<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Service\SignupService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Contracts\Translation\TranslatorInterface;

class SecurityController extends AbstractController
{
    /**
     * @Route("/{_locale}/login", name="app_login", requirements={"_locale"="%app.locales%"})
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    public function loginFormPaintingViewOne(AuthenticationUtils $authenticationUtils): Response
    {
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login_form_painting_view_one.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    /**
     * @Route("/logout", name="app_logout")
     */
    public function logout(){}

    /**
     * @Route("/{_locale}/sign-up", name="signup", requirements={"_locale"="%app.locales%"})
     */
    public function subscribe(Request $request, UserPasswordEncoderInterface $passwordEncoder, SignupService $service, TranslatorInterface $translator)
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $em = $this->getDoctrine()->getManager();
            $hash = $passwordEncoder->encodePassword($user, $user->getPassword());
            $user->setPassword($hash);
            $em->persist($user);
            $em->flush();

            $service->registrationEmail($user);
            $translated = $translator->trans('You are now registered to Art Gallery. You can now login and share with other users');
            $this->addFlash('success', $translated);

            $this->redirectToRoute('homepage', ['_locale' => $request->getLocale()]);
        }

        return $this->render('security/signup.html.twig', ['form' => $form->createView()]);
    }
}
