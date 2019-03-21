<?php

namespace App\Controller;

use App\Entity\Painting;
use App\Entity\PaintingMedia;
use App\Form\PaintingMediaEditType;
use App\Form\PaintingMediaType;
use App\Repository\PaintingMediaRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * @Route("/painting/media", name="painting_media_")
 */
class PaintingMediaController extends AbstractController
{
    /**
     * @var EntityManagerInterface
     */
    private $em;
    /**
     * @var PaintingMediaRepository
     */
    private $mediaRepo;
    /**
     * @var TranslatorInterface
     */
    private $translator;

    public function __construct(EntityManagerInterface $em, PaintingMediaRepository $mediaRepo, TranslatorInterface $translator)
    {
        $this->em = $em;
        $this->mediaRepo = $mediaRepo;
        $this->translator = $translator;
    }

    /**
     * @Route("/", name="all")
     */
    public function viewMedias()
    {
        $medias = $this->mediaRepo->findAll();

        return $this->render('painting_media/media.html.twig', ['medias' => $medias]);
    }

    /**
     * @Route("/view/{id}", name="view_one")
     */
    public function viewOneMedia($id)
    {
        $media = $this->mediaRepo->findById($id);
        $paintings = $this->getDoctrine()->getRepository(Painting::class)->findByMedia($id);

        return $this->render('painting_media/media_view_one.html.twig', ['media' => $media, 'paintings' => $paintings]);
    }

    /******************************************************************************************************************
     * Access denied unless granted below
     *****************************************************************************************************************/

    /**
     * @Route("/add", name="add")
     */
    public function addOneMedia(Request $request)
    {
        $media = new PaintingMedia();
        $form = $this->createForm(PaintingMediaType::class, $media);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $this->em->persist($media);
            $this->em->flush();

            $translated = $this->translator->trans('Media %media% successfully added', ['%media%' => $media->getName()]);
            $this->addFlash('success', $translated);
            return $this->redirectToRoute('homepage');
        }

        return $this->render('painting_media/media_add.html.twig', ['media' => $media, 'form' => $form->createView()]);
    }

    /**
     * @Route("/edit/{id}", name="edit_one")
     */
    public function editOneMedia(Request $request, PaintingMedia $media)
    {
        $form = $this->createForm(PaintingMediaEditType::class, $media);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $this->em->persist($media);
            $this->em->flush();

            $translated = $this->translator->trans('Media %media% successfully modified', ['%media%' => $media->getName()]);
            $this->addFlash('success', $translated);
            return $this->redirectToRoute('homepage');
        }

        return $this->render('painting_media/media_edit.html.twig', ['media' => $media, 'form' => $form->createView()]);
    }

    /**
     * @Route("/delete/{id}", name="delete_one")
     */
    public function deleteOneMedia($id)
    {
        $media = $this->mediaRepo->find($id);
        $this->em->remove($media);
        $this->em->flush();

        $translated = $this->translator->trans('Media %media% successfully removed', ['%media%' => $media->getName()]);
        $this->addFlash('success', $translated);
        return $this->redirectToRoute('homepage');
    }
}
