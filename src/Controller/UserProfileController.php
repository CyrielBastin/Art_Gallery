<?php

namespace App\Controller;

use App\Entity\UserProfile;
use App\Form\UserProfileType;
use App\Repository\UserProfileRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/{_locale}/user/profile", name="user_profile_", requirements={"_locale"="%app.locales%"})
 */
class UserProfileController extends AbstractController
{
    /**
     * @var EntityManagerInterface
     */
    private $em;
    /**
     * @var UserProfileRepository
     */
    private $userProfileRepository;

    public function __construct(EntityManagerInterface $em, UserProfileRepository $userProfileRepository)
    {
        $this->em = $em;
        $this->userProfileRepository = $userProfileRepository;
    }

    /**
     * @Route("/view-{pseudo}", name="view_one")
     */
    public function userProfile($pseudo)
    {
        $user_profile = $this->userProfileRepository->findOneByPseudo($pseudo);
        if($user_profile['role'] === null){
            $user_profile['role'] = 'User';
        }else{
            $temp = explode('_', $user_profile['role']);
            $user_profile['role'] = ucfirst(strtolower($temp[1]));
        }

        return $this->render('user_profile/user_profile_view_one.html.twig', ['profile' => $user_profile]);
    }

    /******************************************************************************************************************
     * Access denied unless granted below
     *****************************************************************************************************************/

    /**
     * @Route("/modify/edit-{pseudo}", name="edit_one")
     */
    public function editUserProfile(Request $request, UserProfile $userProfile)
    {
        $form = $this->createForm(UserProfileType::class, $userProfile);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $form_values = $request->request->all();
            $userProfile->getUser()->setEmail($form_values['user_profile']['email']);

            $this->em->persist($userProfile);
            $this->em->flush();
            $userProfile->setImageFile(null);

            $this->addFlash('info', 'Your profile has been updated');
            return $this->redirectToRoute('user_profile_view_one', ['_locale' => $request->getLocale(), 'pseudo' => $userProfile->getPseudo()]);
        }

        return $this->render('user_profile/user_profile_edit_one.html.twig', ['profile' => $userProfile, 'form' => $form->createView()]);
    }

}
