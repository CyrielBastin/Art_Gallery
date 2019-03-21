<?php

namespace App\Controller;

use App\Entity\PaintingComment;
use App\Form\PaintingCommentType;
use App\Repository\PaintingCommentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/{_locale}/painting-comment", name="painting_comment_", requirements={"_locale"="%app.locales%"})
 */
class PaintingCommentController extends AbstractController
{
    /**
     * @var EntityManagerInterface
     */
    private $em;
    /**
     * @var PaintingCommentRepository
     */
    private $paintingCommentRepository;

    public function __construct(EntityManagerInterface $em, PaintingCommentRepository $paintingCommentRepository)
    {
        $this->em = $em;
        $this->paintingCommentRepository = $paintingCommentRepository;
    }

    /******************************************************************************************************************
     * Access denied unless granted below
     *****************************************************************************************************************/

    /**
     * @Route("/commentary/edit-user-comment-{id}/on-painting-{painting_id}", name="edit_user_comment")
     * @param $painting_id
     * @param PaintingComment $paintingComment
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function editOneUserComment(PaintingComment $paintingComment, Request $request, $painting_id)
    {
        $form = $this->createForm(PaintingCommentType::class, $paintingComment);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $this->em->persist($paintingComment);
            $this->em->flush();

            $this->addFlash('success','Your comment has been successfully modified');
            return $this->redirectToRoute('painting_view_one', ['id' => $painting_id]);
        }

        return $this->render('painting_comment/painting_comment_edit.html.twig', ['commentary' => $paintingComment, 'form' => $form->createView()]);
    }

    /**
     * @Route("/commentary/delete-user-comment/commentary-{commentary_id}-on-painting-{painting_id}", name="delete_user_comment")
     * @param $commentary_id
     * @param $painting_id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteOneUserComment($commentary_id, $painting_id)
    {
        $painting_comment = $this->paintingCommentRepository->find($commentary_id);
        $this->em->remove($painting_comment);
        $this->em->flush();

        $this->addFlash('success', 'You comment has been successfully deleted');
        return $this->redirectToRoute('painting_view_one', ['id' => $painting_id]);
    }

}
