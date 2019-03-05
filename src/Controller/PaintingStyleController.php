<?php

namespace App\Controller;

use App\Entity\PaintingStyle;
use App\Form\PaintingStyleType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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

    /******************************************************************************************************************
     * Access denied unless granted below
     *****************************************************************************************************************/

    /**
     * @Route("/add", name="add")
     */
    public function addOneStyle(Request $request)
    {
        $style = new PaintingStyle();
        $form = $this->createForm(PaintingStyleType::class, $style);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $em = $this->getDoctrine()->getManager();
            $em->persist($style);
            $em->flush();

            return $this->redirectToRoute('homepage');
        }

        return $this->render('painting_style/style_add.html.twig', ['style' => $style, 'form' => $form->createView()]);
    }

    /**
     * @Route("/edit/{id}", name="edit_one")
     */
    public function editOneStyle(Request $request, PaintingStyle $style)
    {
        $form = $this->createForm(PaintingStyleType::class, $style);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $em = $this->getDoctrine()->getManager();
            $em->persist($style);
            $em->flush();

            return $this->redirectToRoute('homepage');
        }
        return $this->render('painting_style/style_edit.html.twig', ['style' => $style, 'form' => $form->createView()]);
    }

    /**
     * @Route("/delete/{id}", name="delete_one")
     */
    public function deleteOneStyle($id)
    {
        $em = $this->getDoctrine()->getManager();
        $repository = $this->getDoctrine()->getRepository(PaintingStyle::class);
        $style = $repository->find($id);
        $em->remove($style);
        $em->flush();

        return $this->redirectToRoute('homepage');
    }
}
