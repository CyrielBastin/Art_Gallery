<?php

namespace App\Controller;

use App\Entity\Painting;
use App\Entity\PaintingStyle;
use App\Form\PaintingStyleEditType;
use App\Form\PaintingStyleType;
use App\Repository\PaintingStyleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/painting/style", name="painting_style_")
 */
class PaintingStyleController extends AbstractController
{
    /**
     * @var EntityManagerInterface
     */
    private $em;
    /**
     * @var PaintingStyleRepository
     */
    private $styleRepo;

    public function __construct(EntityManagerInterface $em, PaintingStyleRepository $styleRepo)
    {
        $this->em = $em;
        $this->styleRepo = $styleRepo;
    }

    /**
     * @Route("/", name="all")
     */
    public function viewStyles()
    {
        $styles = $this->styleRepo->findAll();

        return $this->render('painting_style/style.html.twig', ['styles' => $styles]);
    }

    /**
     * @Route("/view/{id}", name="view_one")
     */
    public function viewOneStyle($id)
    {
        $style = $this->styleRepo->findById($id);
        $paintings = $this->getDoctrine()->getRepository(Painting::class)->findByStyle($id);

        return $this->render('painting_style/style_view_one.html.twig', ['style' => $style, 'paintings' => $paintings]);
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
            $this->em->persist($style);
            $this->em->flush();

            $this->addFlash('success', 'Style ' . $style->getName() . ' successfully added');
            return $this->redirectToRoute('homepage');
        }

        return $this->render('painting_style/style_add.html.twig', ['style' => $style, 'form' => $form->createView()]);
    }

    /**
     * @Route("/edit/{id}", name="edit_one")
     */
    public function editOneStyle(Request $request, PaintingStyle $style)
    {
        $form = $this->createForm(PaintingStyleEditType::class, $style);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $this->em->persist($style);
            $this->em->flush();

            $this->addFlash('success', 'Style ' . $style->getName() . ' successfully modified');
            return $this->redirectToRoute('homepage');
        }
        return $this->render('painting_style/style_edit.html.twig', ['style' => $style, 'form' => $form->createView()]);
    }

    /**
     * @Route("/delete/{id}", name="delete_one")
     */
    public function deleteOneStyle($id)
    {
        $style = $this->styleRepo->find($id);
        $this->em->remove($style);
        $this->em->flush();

        $this->addFlash('success', 'Style ' . $style->getName() . ' successfully removed');
        return $this->redirectToRoute('homepage');
    }
}
