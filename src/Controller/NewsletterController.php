<?php

namespace App\Controller;

use App\Entity\NewsletterMessages;
use App\Entity\NewsletterSubscribedPeople;
use App\Form\NewsletterMessageType;
use App\Service\NewsletterService;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/newsletter", name="newsletter_")
 */
class NewsletterController extends AbstractController
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @Route("/subscribe", name="subscribe")
     */
    public function subscribeToNewsletter(Request $request)
    {
        $newsletter = new NewsletterSubscribedPeople();
        $form = $request->request->all();
        $email = $form['email'];

        if($email != null)
        {
            $subcribers = $this->em->getRepository(NewsletterSubscribedPeople::class)->findAll();
            $exists = false;
            foreach ($subcribers as $subcriber){
                if($email == $subcriber['email']){
                    $exists = true;
                    break;
                }
            }

            if($exists){
                $this->addFlash('danger', 'You are already registered to our Newsletter');
                $this->redirectToRoute('homepage');
            }else{
                $newsletter->setEmail($email);
                $this->em->persist($newsletter);
                $this->em->flush();

                $this->addFlash('success', 'You are now subscribed to our Newsletter. Congratulations !');
                $this->redirectToRoute('homepage');
            }
        }

        return $this->render('home_page/index.html.twig');
    }

    /******************************************************************************************************************
     * Access denied unless granted below
     *****************************************************************************************************************/

    /**
     * @Route("/view-all", name="view_all")
     */
    public function viewAllNewsletter(PaginatorInterface $paginator, Request $request)
    {
        $newsletter_messages = $this->getDoctrine()->getRepository(NewsletterMessages::class)->findAll();

        $pagination = $paginator->paginate($newsletter_messages, $request->query->getInt('page', 1), 6);

        return $this->render('newsletter/newsletter_view_all.html.twig', ['newsletters' => $pagination]);
    }

    /**
     * @Route("/create-one", name="create_one")
     */
    public function createNewsletter(Request $request, NewsletterService $service)
    {
        $newsletter = new NewsletterMessages();
        $form = $this->createForm(NewsletterMessageType::class, $newsletter);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $newsletter->setCreatedAt(new \DateTime('now'));
            $this->em->persist($newsletter);
            $this->em->flush();

            $service->sendNewsletter($newsletter);
            return $this->redirectToRoute('redirect_from_newsletter_create_one');
        }

        return $this->render('newsletter/newsletter_create_one.html.twig', ['form' => $form->createView()]);
    }
}
