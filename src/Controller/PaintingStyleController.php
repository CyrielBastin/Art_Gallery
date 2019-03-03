<?php

namespace App\Controller;

use App\Entity\PaintingStyle;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/painting/style", name="painting_style_")
 */
class PaintingStyleController extends AbstractController
{
    /**
     * @Route("/", name="all")
     */
    public function viewStyles()
    {
        $styles = $this->getDoctrine()->getRepository(PaintingStyle::class)->findAll();

        return $this->render('painting_style/style.html.twig', ['styles' => $styles]);
    }

    /**
     * @Route("/view/{id}", name="view_one")
     */
    public function viewOneStyle($id)
    {
        $style = $this->getDoctrine()->getRepository(PaintingStyle::class)->findById($id);

        return $this->render('painting_style/style_view_one.html.twig', ['style' => $style]);
    }
}
