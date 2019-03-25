<?php

namespace App\Controller;


use App\Entity\User;
use App\Entity\UserProfile;
use App\Form\UserDashboardType;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * @Route("/{_locale}/admin/users", name="admin_user_", requirements={"_locale"="%app.locales%"})
 */
class AdminUserController extends AbstractController
{
    /**
     * @var EntityManagerInterface
     */
    private $em;
    /**
     * @var TranslatorInterface
     */
    private $translator;

    public function __construct(EntityManagerInterface $em, TranslatorInterface $translator)
    {
        $this->em = $em;
        $this->translator = $translator;
    }

    /**
     * @Route("/list", name="list")
     */
    public function showListUsers(PaginatorInterface $paginator, Request $request)
    {
        $users = $this->getDoctrine()->getRepository(User::class)->adminListUser();

        $pagination = $paginator->paginate($users, $request->query->getInt('page', 1), 15);

        return $this->render('admin/user/user_list.html.twig', ['users' => $pagination]);
    }

    /**
     * @Route("/edit-one/{pseudo}/edit-from-dashboard", name="edit")
     */
    public function editUserDashboard(Request $request, UserProfile $userProfile)
    {
        $form = $this->createForm(UserDashboardType::class, $userProfile);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $form_values = $request->request->all();
            $userProfile->getUser()->setEmail($form_values['user_dashboard']['email']);
            $this->em->getRepository(User::class)->adminSetRole($userProfile->getUser()->getId(), $form_values['user_dashboard']['role']);

            $this->em->persist($userProfile);
            $this->em->flush();

            $translated = $this->translator->trans('The user profile %user% has been updated', ['%user%' => $userProfile->getPseudo()]);
            $this->addFlash('info', $translated);

            return $this->redirectToRoute('admin_user_list', ['_locale' => $request->getLocale()]);
        }

        return $this->render('admin/user/user_edit_one.html.twig', ['user' => $userProfile, 'form' => $form->createView()]);
    }

    /**
     * @Route("/delete-one/{id}/delete-from-dashboard", name="delete")
     */
    public function deleteUserDashboard(Request $request, $id)
    {
        $user = $this->em->getRepository(User::class)->find($id);
        $this->em->remove($user);
        $this->em->flush();

        $translated = $this->translator->trans('The user profile %user% has been properly deleted', ['%user%' => $user->getUserProfile()->getPseudo()]);
        $this->addFlash('info', $translated);

        return $this->redirectToRoute('admin_user_list', ['_locale' => $request->getLocale()]);
    }

}
