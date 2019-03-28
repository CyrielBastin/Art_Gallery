<?php

namespace App\Controller;


use App\Entity\User;
use App\Entity\UserMessages;
use App\Entity\UserProfile;
use App\Form\UserMessagesType;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * @Route("/{_locale}/admin/users/messages", name="admin_user_message_", requirements={"_locale"="%app.locales%"})
 */
class AdminUserMessageController extends AbstractController
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
     * @Route("/all-users", name="all_users")
     */
    public function adminShowAllUsers(Request $request, PaginatorInterface $paginator)
    {
        $users = $this->em->getRepository(UserProfile::class)->listUser();

        $pagination = $paginator->paginate($users, $request->query->getInt('page', 1), 12);

        return $this->render('admin/user_message/show_all_users.html.twig', ['users' => $pagination]);
    }

    /**
     * @Route("/see-all-{pseudo}-conversations", name="see_all_conversations")
     */
    public function adminSeeConversationsByUser($pseudo)
    {
        $user = $this->em->getRepository(UserProfile::class)->findOneByPseudo($pseudo);

        $conversations = $this->em->getRepository(UserMessages::class)->findConversationsByUser($user['id']);

        return $this->render('admin/user_message/show_conversations_by_user.html.twig', ['conversations' => $conversations, 'user' => $user]);
    }

    /**
     * @Route("/see-conversation-between/{user_pseudo}-and-{receiver_pseudo}", name="see_conversation_between_users")
     */
    public function adminSeeConversationBetweenUsers(Request $request, PaginatorInterface $paginator, $user_pseudo, $receiver_pseudo)
    {
        $user = $this->em->getRepository(UserProfile::class)->findOneByPseudo($user_pseudo);
        $other_user = $this->em->getRepository(UserProfile::class)->findOneByPseudo($receiver_pseudo);
        $messages = $this->em->getRepository(UserMessages::class)->findMessagesPerConversation($user['id'], $other_user['id']);

        $pagination = $paginator->paginate($messages, $request->query->getInt('page', 1), 6);

        return $this->render('admin/user_message/show_conversation_between_users.html.twig', ['messages' => $pagination, 'user' => $user, 'other_user' => $other_user]);
    }

    /**
     * @Route("/conversation-between/{user_pseudo}-and-{receiver_pseudo}/message-{id}-edit", name="edit_a_message")
     */
    public function adminEditAMessage(Request $request, UserMessages $messages, $user_pseudo, $receiver_pseudo)
    {
        $form = $this->createForm(UserMessagesType::class, $messages);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $this->em->persist($messages);
            $this->em->flush();

            $translated = $this->translator->trans('The message has been edited with success');
            $this->addFlash('info', $translated);

            return $this->redirectToRoute('admin_user_message_see_conversation_between_users', ['_locale' => $request->getLocale(), 'user_pseudo' => $user_pseudo, 'receiver_pseudo' => $receiver_pseudo]);
        }

        return $this->render('admin/user_message/edit_a_message.html.twig', ['form' => $form->createView(), 'user' => $user_pseudo, 'receiver' => $receiver_pseudo]);
    }

    /**
     * @Route("/conversation-between/{user_pseudo}-and-{receiver_pseudo}/message-{id}-delete", name="delete_a_message")
     */
    public function adminDeleteAMessage(Request $request, $id, $user_pseudo, $receiver_pseudo)
    {
        $message = $this->em->getRepository(UserMessages::class)->findOneBy(['id' => $id]);
        $this->em->remove($message);
        $this->em->flush();

        $translated = $this->translator->trans('The message has been deleted with success');
        $this->addFlash('info', $translated);

        return $this->redirectToRoute('admin_user_message_see_conversation_between_users', ['_locale' => $request->getLocale(), 'user_pseudo' => $user_pseudo, 'receiver_pseudo' => $receiver_pseudo]);
    }

    /**
     * @Route("/conversation/message-to-{receiver_pseudo}/send-a-message", name="send_a_message")
     */
    public function adminSendAMessage(Request $request, $receiver_pseudo)
    {
        $userMessage = new UserMessages();
        $form = $this->createForm(UserMessagesType::class, $userMessage);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $receiver_array = $this->em->getRepository(UserProfile::class)->findOneByPseudo($receiver_pseudo);
            $receiver = $this->em->getRepository(User::class)->findOneBy(['id' => $receiver_array['id']]);

            $userMessage->setSender($this->getUser());
            $userMessage->setReceiver($receiver);
            $userMessage->setPostedAt(new \DateTime('now'));

            $this->em->persist($userMessage);
            $this->em->flush();

            $translated = $this->translator->trans('Your message to %user% has been sent', ['%user%' => $receiver_pseudo]);
            $this->addFlash('info', $translated);

            return $this->redirectToRoute('admin_user_message_see_conversation_between_users', ['_locale' => $request->getLocale(), 'user_pseudo' => $this->getUser()->getUserProfile()->getPseudo(), 'receiver_pseudo' => $receiver_pseudo]);
        }

        return $this->render('admin/user_message/send_a_message.html.twig', ['receiver_pseudo' => $receiver_pseudo, 'form' => $form->createView()]);
    }
}
