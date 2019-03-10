<?php

namespace App\Controller;

use App\Entity\Painting;
use App\Entity\PaintingMedia;
use App\Form\PaintingMediaType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/painting/media", name="painting_media_")
 */
class PaintingMediaController extends AbstractController
{
    /**
     * @Route("/", name="all")
     */
    public function viewMedias()
    {
        $medias = $this->getDoctrine()->getRepository(PaintingMedia::class)->findAll();

        return $this->render('painting_media/media.html.twig', ['medias' => $medias]);
    }

    /**
     * @Route("/view/{id}", name="view_one")
     */
    public function viewOneMedia($id)
    {
        $media = $this->getDoctrine()->getRepository(PaintingMedia::class)->findById($id);
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
            $em = $this->getDoctrine()->getManager();
            $em->persist($media);
            $em->flush();

            $this->addFlash('success', 'Media ' . $media->getName() . ' successfully added');
            return $this->redirectToRoute('homepage');
        }

        return $this->render('painting_media/media_add.html.twig', ['media' => $media, 'form' => $form->createView()]);
    }

    /**
     * @Route("/edit/{id}", name="edit_one")
     */
    public function editOneMedia(Request $request, PaintingMedia $media)
    {
        $form = $this->createForm(PaintingMediaType::class, $media);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $em = $this->getDoctrine()->getManager();
            $em->persist($media);
            $em->flush();

            $this->addFlash('success', 'Media ' . $media->getName() . ' successfully modified');
            return $this->redirectToRoute('homepage');
        }

        return $this->render('painting_media/media_edit.html.twig', ['media' => $media, 'form' => $form->createView()]);
    }

    /**
     * @Route("/delete/{id}", name="delete_one")
     */
    public function deleteOneMedia($id)
    {
        $em = $this->getDoctrine()->getManager();
        $repository = $this->getDoctrine()->getRepository(PaintingMedia::class);
        $media = $repository->find($id);
        $em->remove($media);
        $em->flush();

        $this->addFlash('success', 'Media ' . $media->getName() . ' successfully removed');
        return $this->redirectToRoute('homepage');
    }
}
