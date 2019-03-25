<?php

namespace App\Controller;


use App\Entity\Painting;
use App\Entity\PaintingComment;
use App\Entity\User;
use App\Form\PaintingCommentType;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/{_locale}/admin/comments", name="admin_comment_", requirements={"_locale"="%app.locales%"})
 */
class AdminCommentController extends AbstractController
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
     * @Route("/overview", name="overview")
     */
    public function showCommentsOverview(PaginatorInterface $paginator, Request $request)
    {
        $users = $this->getDoctrine()->getRepository(User::class)->adminCommentsUserOverview();
        $paintings = $this->getDoctrine()->getRepository(Painting::class)->adminCommentsPaintingOverview();

        $pagination_user = $paginator->paginate($users, $request->query->getInt('page', 1), 15);
        $pagination_painting = $paginator->paginate($paintings, $request->query->getInt('page', 1), 15);

        return $this->render('admin/comment/comment_user_overview.html.twig', ['users' => $pagination_user, 'paintings' => $pagination_painting]);
    }

    /**
     * @Route("/by-users/{user_id}/view/comments", name="by_user")
     */
    public function showCommentsByUser($user_id, PaginatorInterface $paginator, Request $request)
    {
        $user = $this->getDoctrine()->getRepository(User::class)->adminCommentByUser($user_id);
        $paintings = $this->getDoctrine()->getRepository(Painting::class)->adminPaintingCommentByUser($user_id);

        $pagination = $paginator->paginate($paintings, $request->query->getInt('page', 1), 8);

        return $this->render('admin/comment/comment_user_user_detail.html.twig', ['user' => $user, 'paintings' => $pagination]);
    }

    public function generateUserCommentsByPainting($user_id, $painting_id)
    {
        $comments = $this->getDoctrine()->getRepository(Painting::class)->adminCommentsUserByPaintingId($user_id, $painting_id);

        return $this->render('admin/comment/comment_by_user_per_painting.html.twig', ['comments' => $comments]);
    }

    /**
     * @Route("/edit-one/{id}/with-user-{user_id}/edit-from-dashboard", name="edit")
     */
    public function editCommentUserDashboard(PaintingComment $paintingComment, Request $request, $user_id)
    {
        $user = $this->getDoctrine()->getRepository(User::class)->findOneBy(['id' => $user_id]);
        $form = $this->createForm(PaintingCommentType::class, $paintingComment);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $this->em->persist($paintingComment);
            $this->em->flush();

            return $this->redirectToRoute('redirect_from_admin_comment_edit', ['user_id' => $user_id]);
        }

        return $this->render('admin/comment/comment_user_edit_one.html.twig', ['form' => $form->createView(), 'user' => $user]);
    }

    /**
     * @Route("/delete-one/{commentary_id}/delete-{user_id}-from-dashboard", name="delete")
     */
    public function deleteCommentUserDashboard($commentary_id, $user_id)
    {
        $commentary = $this->getDoctrine()->getRepository(PaintingComment::class)->find($commentary_id);
        $this->em->remove($commentary);
        $this->em->flush();

        return $this->redirectToRoute('redirect_from_admin_comment_delete', ['user_id' => $user_id]);
    }

    /**
     * @Route("/by-paintings/{painting_id}/view/comments", name="by_paintings")
     */
    public function showCommentsByPaintings($painting_id, Request $request, PaginatorInterface $paginator)
    {
        $painting = $this->getDoctrine()->getRepository(Painting::class)->findById($painting_id);
        $painting_commentary = $this->getDoctrine()->getRepository(PaintingComment::class)->findByPaintingId($painting_id);
        $pagination = $paginator->paginate($painting_commentary, $request->query->getInt('page', 1), 7);

        $paintingComment = new PaintingComment();
        $form = $this->createForm(PaintingCommentType::class, $paintingComment);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $paintingComment->setUser($this->getUser());
            $paintingComment->setPainting($this->getDoctrine()->getRepository(Painting::class)->findOneBy(['id' => $painting_id]));
            $paintingComment->setPostedAt(new \DateTime('now'));
            //
            $this->em->persist($paintingComment);
            $this->em->flush();

            return $this->redirectToRoute('redirect_from_admin_comment_by_painting', ['painting_id' => $painting_id]);
        }

        return $this->render('admin/comment/comment_user_painting_detail.html.twig', ['painting' => $painting, 'commentaries' => $pagination, 'form' => $form->createView()]);
    }

    /**
     * @Route("/by-paintings/edit-{id}/comment-from/{painting_id}/{user_id}/user", name="by_paintings_edit")
     */
    public function editAdminCommentByPainting(Request $request, PaintingComment $paintingComment, $painting_id, $user_id)
    {
        $user = $this->getDoctrine()->getRepository(User::class)->findOneBy(['id' => $user_id]);
        $form = $this->createForm(PaintingCommentType::class, $paintingComment);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $this->em->persist($paintingComment);
            $this->em->flush();

            return $this->redirectToRoute('admin_comment_by_paintings', ['_locale' => $request->getLocale(), 'painting_id' => $painting_id]);
        }

        return $this->render('admin/comment/comment_user_edit_one.html.twig', ['form' => $form->createView(), 'user' => $user]);
    }

    /**
     * @Route("/by-paintings/delete-{painting_comment_id}/on-painting-{painting_id}/delete-from-dashboard", name="by_paintings_delete")
     */
    public function deleteAdminCommentByPainting(Request $request, $painting_comment_id, $painting_id)
    {
        $commentary = $this->getDoctrine()->getRepository(PaintingComment::class)->find($painting_comment_id);
        $this->em->remove($commentary);
        $this->em->flush();

        return $this->redirectToRoute('admin_comment_by_paintings', ['_locale' => $request->getLocale(), 'painting_id' => $painting_id]);
    }

}
