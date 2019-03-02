<?php

namespace App\Controller;

use App\Entity\Painting;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/painting", name="painting_")
 */
class PaintingController extends AbstractController
{
    /**
     * @Route("/", name="all")
     */
    public function index(Request $request, PaginatorInterface $paginator)
    {
        $paintings = $this->getDoctrine()->getRepository(Painting::class)->findAll();

        $pagination = $paginator->paginate($paintings, $request->query->getInt('page', 1), 10);

        return $this->render('painting/painting.html.twig', ['paintings' => $pagination]);
    }
}
