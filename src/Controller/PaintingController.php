<?php

namespace App\Controller;

use App\Entity\Painting;
use App\Form\PaintingType;
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

    /**
     * @Route("/discount", name="discount")
     */
    public function viewDiscount()
    {
        $discounts = $this->getDoctrine()->getRepository(Painting::class)->findDiscount();

        return $this->render('painting/painting_discount.html.twig', ['discounts' => $discounts]);
    }

    /******************************************************************************************************************
     * Access denied unless granted below
     *****************************************************************************************************************/

    /**
     * @Route("/add", name="add")
     */
    public function addOnePainting(Request $request)
    {
        $painting = new Painting();
        $form = $this->createForm(PaintingType::class, $painting);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $em = $this->getDoctrine()->getManager();
            $em->persist($painting);
            $em->flush();

            $this->addFlash('success', 'The artwork ' . $painting->getTitle() . ' has been correctly added');
            return $this->redirectToRoute('homepage');
        }

        return $this->render('painting/painting_add.html.twig', ['painting' => $painting, 'form' => $form->createView()]);
    }

    /**
     * @Route("/edit/{id}", name="edit_one")
     */
    public function editOnePainting(Request $request, Painting $painting)
    {
        $form = $this->createForm(PaintingType::class, $painting);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $em = $this->getDoctrine()->getManager();
            $em->persist($painting);
            $em->flush();

            $this->addFlash('success', 'The artwork ' . $painting->getTitle() . ' has been correctly modified');
            return $this->redirectToRoute('homepage');
        }
        return $this->render('painting/painting_edit.html.twig', ['painting' => $painting, 'form' => $form->createView()]);
    }

    /**
     * @Route("/delete/{id}", name="delete_one")
     */
    public function deleteOnePainting($id)
    {
        $em = $this->getDoctrine()->getManager();
        $repository = $this->getDoctrine()->getRepository(Painting::class);
        $painting = $repository->find($id);
        $em->remove($painting);
        $em->flush();

        $this->addFlash('success', 'The artwork ' . $painting->getTitle() . ' has been properly deleted');
        return $this->redirectToRoute('homepage');
    }
}
