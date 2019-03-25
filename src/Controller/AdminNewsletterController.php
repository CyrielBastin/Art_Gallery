<?php

namespace App\Controller;


use App\Entity\NewsletterMessages;
use App\Form\NewsletterMessageType;
use App\Service\NewsletterService;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/{_locale}/admin/newsletter", name="admin_newsletter_", requirements={"_locale"="%app.locales%"})
 */
class AdminNewsletterController extends AbstractController
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
     * @Route("/view-all", name="view_all")
     */
    public function adminViewAllNewsletter(PaginatorInterface $paginator, Request $request)
    {
        $newsletter_messages = $this->getDoctrine()->getRepository(NewsletterMessages::class)->findAll();

        $pagination = $paginator->paginate($newsletter_messages, $request->query->getInt('page', 1), 6);

        return $this->render('admin/newsletter/newsletter_list.html.twig', ['newsletters' => $pagination]);
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
            return $this->redirectToRoute('redirect_from_admin_newsletter_create_one');
        }

        return $this->render('admin/newsletter/newsletter_create_one.html.twig', ['form' => $form->createView()]);
    }

}
