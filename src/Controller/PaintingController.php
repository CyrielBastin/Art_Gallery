<?php

namespace App\Controller;

use App\Entity\Painting;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/painting", name="painting_")
 */
class PaintingController extends AbstractController
{
    /**
     * @Route("/", name="all")
     */
    public function index()
    {
        $paintings = $this->getDoctrine()->getRepository(Painting::class)->findAll();

        return $this->render('painting/painting.html.twig', ['paintings' => $paintings]);
    }
}
