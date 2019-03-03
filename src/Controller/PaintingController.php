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
    public function viewAll(Request $request, PaginatorInterface $paginator)
    {
        $paintings = $this->getDoctrine()->getRepository(Painting::class)->findAll();

        $pagination = $paginator->paginate($paintings, $request->query->getInt('page', 1), 10);

        return $this->render('painting/painting.html.twig', ['paintings' => $pagination]);
    }

    /**
     * @Route("/view/{id}", name="view_one")
     */
    public function viewOne($id)
    {
        $painting = $this->getDoctrine()->getRepository(Painting::class)->findById($id);

        return $this->render('painting/painting_view_one.html.twig', ['painting' => $painting]);
    }

    /**
     * @Route("/latest-added", name="latest_added")
     */
    public function viewLatest()
    {
        $paintings = $this->getDoctrine()->getRepository(Painting::class)->findLatest();

        return $this->render('painting/painting_latest_added.html.twig', ['paintings' => $paintings]);
    }
}
