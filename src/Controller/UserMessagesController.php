<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\UserMessages;
use App\Entity\UserProfile;
use App\Form\UserMessagesType;
use App\Repository\UserMessagesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * @Route("/{_locale}/user/messages", name="user_message_", requirements={"_locale"="%app.locales%"})
 */
class UserMessagesController extends AbstractController
{
    /**
     * @var EntityManagerInterface
     */
    private $em;
    /**
     * @var UserMessagesRepository
     */
    private $userMessagesRepository;
    /**
     * @var TranslatorInterface
     */
    private $translator;

    public function __construct(EntityManagerInterface $em, UserMessagesRepository $userMessagesRepository, TranslatorInterface $translator)
    {
        $this->em = $em;
        $this->userMessagesRepository = $userMessagesRepository;
        $this->translator = $translator;
    }

    /**
     * @Route("/show/conversations/all", name="show_conversations")
     */
    public function showConversations()
    {
        $conversations = $this->userMessagesRepository->findConversationsByUser($this->getUser()->getId());

        return $this->render('user_messages/show_conversations.html.twig', ['conversations' => $conversations]);
    }

    /**
     * @Route("/conversation-with-{pseudo}/show", name="show_one_conversation")
     */
    public function showOneConversation($pseudo, Request $request, PaginatorInterface $paginator)
    {
        $other_user = $this->em->getRepository(UserProfile::class)->findOneByPseudo($pseudo);
        $messages = $this->userMessagesRepository->findMessagesPerConversation($this->getUser()->getId(), $other_user['id']);

        $pagination = $paginator->paginate($messages, $request->query->getInt('page', 1), 6);

        return $this->render('user_messages/show_one_conversation.html.twig', ['messages' => $pagination, 'other_user' => $other_user]);
    }

    /**
     * @Route("/conversation-with-{pseudo}/send-a-message", name="send_a_message")
     */
    public function sendAMessage($pseudo, Request $request)
    {
        $message = new UserMessages();
        $form = $this->createForm(UserMessagesType::class, $message);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $receiver_profile = $this->em->getRepository(UserProfile::class)->findOneByPseudo($pseudo);
            $receiver = $this->em->getRepository(User::class)->findOneBy(['id' => $receiver_profile['id']]);

            $message->setSender($this->getUser());
            $message->setReceiver($receiver);
            $message->setPostedAt(new \DateTime('now'));

            $this->em->persist($message);
            $this->em->flush();

            $translated = $this->translator->trans('Your message to %user% has been sent', ['%user%' => $receiver_profile['pseudo']]);
            $this->addFlash('info', $translated);

            return $this->redirectToRoute('user_message_show_one_conversation', ['_locale' => $request->getLocale(), 'pseudo' => $pseudo]);
        }

        return $this->render('user_messages/send_a_message.html.twig', ['message' => $message, 'receiver' => $pseudo, 'form' => $form->createView()]);
    }

    /**
     * @Route("/conversation-with-{pseudo}/message-{id}-edit", name="edit_a_message")
     */
    public function editAMessage(UserMessages $userMessages, Request $request, $pseudo)
    {
        $form = $this->createForm(UserMessagesType::class, $userMessages);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $this->em->persist($userMessages);
            $this->em->flush();

            $translated = $this->translator->trans('Your message to %user% has been modified', ['%user%' => $pseudo]);
            $this->addFlash('info', $translated);

            return $this->redirectToRoute('user_message_show_one_conversation', ['_locale' => $request->getLocale(), 'pseudo' => $pseudo]);
        }

        return $this->render('user_messages/edit_a_message.html.twig', ['receiver' => $pseudo, 'form' => $form->createView()]);
    }

    /**
     * @Route("/conversation-with-{pseudo}/message-{message_id}-delete", name="delete_a_message")
     */
    public function deleteAMessage($message_id, Request $request, $pseudo)
    {
        $message = $this->em->getRepository(UserMessages::class)->findOneBy(['id' => $message_id]);

        $this->em->remove($message);
        $this->em->flush();

        $translated = $this->translator->trans('Your message to %user% has been deleted', ['%user%' => $pseudo]);
        $this->addFlash('info', $translated);

        return $this->redirectToRoute('user_message_show_one_conversation', ['_locale' => $request->getLocale(), 'pseudo' => $pseudo]);
    }
}
