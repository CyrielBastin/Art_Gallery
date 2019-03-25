<?php

namespace App\Controller;


use App\Entity\PaintingStyle;
use App\Form\PaintingStyleEditType;
use App\Form\PaintingStyleType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * @Route("/{_locale}/admin/styles", name="admin_style_", requirements={"_locale"="%app.locales%"})
 */
class AdminStyleController extends AbstractController
{
    /**
     * @var EntityManagerInterface
     */
    private $em;
    /**
     * @var TranslatorInterface
     */
    private $translator;

    public function __construct(EntityManagerInterface $em, TranslatorInterface $translator)
    {
        $this->em = $em;
        $this->translator = $translator;
    }

    /**
     * @Route("/list", name="list")
     */
    public function showListStyles()
    {
        $styles = $this->getDoctrine()->getRepository(PaintingStyle::class)->adminListStyle();

        return $this->render('admin/style/style_list.html.twig', ['styles' => $styles]);
    }

    /**
     * @Route("/add-one/add-from-dashboard", name="add")
     */
    public function addStyleDashboard(Request $request)
    {
        $style = new PaintingStyle();
        $form = $this->createForm(PaintingStyleType::class, $style);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $this->em->persist($style);
            $this->em->flush();

            $translated = $this->translator->trans('Style %style% successfully added', ['%style%' => $style->getName()]);
            $this->addFlash('success', $translated);

            return $this->redirectToRoute('admin_style_list', ['_locale' => $request->getLocale()]);
        }

        return $this->render('admin/style/style_add_one.html.twig', ['style' => $style, 'form' => $form->createView()]);
    }

    /**
     * @Route("/edit-one/{id}/edit-from-dashboard", name="edit")
     */
    public function editStyleDashboard(Request $request, PaintingStyle $style)
    {
        $form = $this->createForm(PaintingStyleEditType::class, $style);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $this->em->persist($style);
            $this->em->flush();

            $translated = $this->translator->trans('Style %style% successfully modified', ['%style%' => $style->getName()]);
            $this->addFlash('success', $translated);

            return $this->redirectToRoute('admin_style_list', ['_locale' => $request->getLocale()]);
        }

        return $this->render('admin/style/style_edit_one.html.twig', ['style' => $style, 'form' => $form->createView()]);
    }

    /**
     * @Route("delete-one/{id}/delete-from-dashboard", name="delete")
     */
    public function deleteStyleDashboard(Request $request, $id)
    {
        $style = $this->em->getRepository(PaintingStyle::class)->find($id);
        $this->em->remove($style);
        $this->em->flush();

        $translated = $this->translator->trans('Style %style% successfully removed', ['%style%' => $style->getName()]);
        $this->addFlash('success', $translated);

        return $this->redirectToRoute('admin_style_list', ['_locale' => $request->getLocale()]);
    }

}
