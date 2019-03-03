<?php

namespace App\Controller;

use App\Entity\PaintingMedia;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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

        return $this->render('painting_media/media_view_one.html.twig', ['media' => $media]);
    }
}
