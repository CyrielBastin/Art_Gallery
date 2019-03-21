<?php

namespace App\Controller;

use App\Entity\Painting;
use App\Entity\PaintingComment;
use App\Form\PaintingCommentType;
use App\Form\PaintingEditType;
use App\Form\PaintingType;
use App\Repository\PaintingRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Util\TargetPathTrait;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * @Route("/{_locale}/painting", name="painting_", requirements={"_locale"="%app.locales%"})
 */
class PaintingController extends AbstractController
{
    use TargetPathTrait;
    /**
     * @var EntityManagerInterface
     */
    private $em;
    /**
     * @var PaintingRepository
     */
    private $paintingRepo;
    /**
     * @var TranslatorInterface
     */
    private $translator;

    public function __construct(EntityManagerInterface $em, PaintingRepository $paintingRepo, TranslatorInterface $translator)
    {
        $this->em = $em;
        $this->paintingRepo = $paintingRepo;
        $this->translator = $translator;
    }

    /**
     * @Route("/", name="all")
     */
    public function viewAll(Request $request, PaginatorInterface $paginator)
    {
        $paintings = $this->paintingRepo->findAll();

        $pagination = $paginator->paginate($paintings, $request->query->getInt('page', 1), 10);

        return $this->render('painting/painting.html.twig', ['paintings' => $pagination]);
    }

    /**
     * @Route("/view/{id}", name="view_one")
     */
    public function viewOne($id, Request $request, PaginatorInterface $paginator)
    {
        $painting = $this->paintingRepo->findById($id);
        $painting_commentary = $this->getDoctrine()->getRepository(PaintingComment::class)->findByPaintingId($id);
        $pagination = $paginator->paginate($painting_commentary, $request->query->getInt('page', 1), 7);

        $paintingComment = new PaintingComment();
        $form = $this->createForm(PaintingCommentType::class, $paintingComment);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $paintingComment->setUser($this->getUser());
            $paintingComment->setPainting($this->paintingRepo->findOneBy(['id' => $id]));
            $paintingComment->setPostedAt(new \DateTime('now'));
            //
            $this->em->persist($paintingComment);
            $this->em->flush();

            return $this->redirectToRoute('redirect_from_painting_view_one', ['painting_id' => $id]);
        }
        $this->saveTargetPath($request->getSession(), 'main', 'http://localhost/symfony/Art_Gallery/public/' . $request->getLocale() . '/painting/view/'.$id.'#post-a-commentary');

        return $this->render('painting/painting_view_one.html.twig', ['painting' => $painting, 'commentaries' => $pagination, 'form' => $form->createView()]);
    }

    /**
     * @Route("/latest-added", name="latest_added")
     */
    public function viewLatest()
    {
        $paintings = $this->paintingRepo->findLatest();

        return $this->render('painting/painting_latest_added.html.twig', ['paintings' => $paintings]);
    }

    /**
     * @Route("/discount", name="discount")
     */
    public function viewDiscount()
    {
        $discounts = $this->paintingRepo->findDiscount();

        return $this->render('painting/painting_discount.html.twig', ['discounts' => $discounts]);
    }

    /******************************************************************************************************************
     * Access denied unless granted below
     *****************************************************************************************************************/

    /**
     * @Route("/add", name="add")
     */
    public function addOnePainting(Request $request)
    {
        $painting = new Painting();
        $form = $this->createForm(PaintingType::class, $painting);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $this->em->persist($painting);
            $this->em->flush();

            $translated = $this->translator->trans('The artwork %painting% has been correctly added', ['%painting%' => $painting->getTitle()]);
            $this->addFlash('success', $translated);
            return $this->redirectToRoute('homepage');
        }

        return $this->render('painting/painting_add.html.twig', ['painting' => $painting, 'form' => $form->createView()]);
    }

    /**
     * @Route("/edit/{id}", name="edit_one")
     */
    public function editOnePainting(Request $request, Painting $painting)
    {
        $form = $this->createForm(PaintingEditType::class, $painting);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $this->em->persist($painting);
            $this->em->flush();

            $translated = $this->translator->trans('The artwork %painting% has been correctly modified', ['%painting%' => $painting->getTitle()]);
            $this->addFlash('success', $translated);
            return $this->redirectToRoute('homepage');
        }
        return $this->render('painting/painting_edit.html.twig', ['painting' => $painting, 'form' => $form->createView()]);
    }

    /**
     * @Route("/delete/{id}", name="delete_one")
     */
    public function deleteOnePainting($id)
    {
        $painting = $this->paintingRepo->find($id);
        $this->em->remove($painting);
        $this->em->flush();

        $translated = $this->translator->trans('The artwork %painting% has been properly deleted', ['%painting%' => $painting->getTitle()]);
        $this->addFlash('success', $translated);
        return $this->redirectToRoute('homepage');
    }

}
