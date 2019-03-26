<?php

namespace App\Controller;

use App\Entity\PaintingComment;
use App\Form\PaintingCommentType;
use App\Repository\PaintingCommentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

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
    /**
     * @var TranslatorInterface
     */
    private $translator;

    public function __construct(EntityManagerInterface $em, PaintingCommentRepository $paintingCommentRepository, TranslatorInterface $translator)
    {
        $this->em = $em;
        $this->paintingCommentRepository = $paintingCommentRepository;
        $this->translator = $translator;
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
        $this->denyAccessUnlessGranted('ROLE_SUPER_ADMIN');

        $form = $this->createForm(PaintingCommentType::class, $paintingComment);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $this->em->persist($paintingComment);
            $this->em->flush();

            $translated = $this->translator->trans('Your comment has been successfully modified');
            $this->addFlash('success',$translated);

            return $this->redirectToRoute('painting_view_one', ['_locale' => $request->getLocale(), 'id' => $painting_id]);
        }

        return $this->render('painting_comment/painting_comment_edit.html.twig', ['commentary' => $paintingComment, 'form' => $form->createView()]);
    }

    /**
     * @Route("/commentary/delete-user-comment/commentary-{commentary_id}-on-painting-{painting_id}", name="delete_user_comment")
     * @param $commentary_id
     * @param $painting_id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteOneUserComment(Request $request, $commentary_id, $painting_id)
    {
        $this->denyAccessUnlessGranted('ROLE_SUPER_ADMIN');

        $painting_comment = $this->paintingCommentRepository->find($commentary_id);
        $this->em->remove($painting_comment);
        $this->em->flush();

        $translated = $this->translator->trans('Your comment has been successfully deleted');
        $this->addFlash('success', $translated);

        return $this->redirectToRoute('painting_view_one', ['_locale' => $request->getLocale(), 'id' => $painting_id]);
    }

}
