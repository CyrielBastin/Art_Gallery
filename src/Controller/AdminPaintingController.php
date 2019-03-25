<?php

namespace App\Controller;


use App\Entity\Painting;
use App\Form\PaintingEditType;
use App\Form\PaintingType;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * @Route("/{_locale}/admin/paintings", name="admin_painting_", requirements={"_locale"="%app.locales%"})
 */
class AdminPaintingController extends AbstractController
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
    public function showListPaintings(PaginatorInterface $paginator, Request $request)
    {
        $paintings = $this->getDoctrine()->getRepository(Painting::class)->adminListPainting();

        $pagination = $paginator->paginate($paintings, $request->query->getInt('page', 1), 12);

        return $this->render('admin/painting/painting_list.html.twig', ['paintings' => $pagination]);
    }

    /**
     * @Route("/add-one/add-from-dashboard", name="add")
     */
    public function addPaintingDashboard(Request $request)
    {
        $painting = new Painting();
        $form = $this->createForm(PaintingType::class, $painting);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $this->em->persist($painting);
            $this->em->flush();

            $translated = $this->translator->trans('The artwork %painting% has been correctly added', ['%painting%' => $painting->getTitle()]);
            $this->addFlash('success', $translated);

            return $this->redirectToRoute('admin_painting_list', ['_locale' => $request->getLocale()]);
        }

        return $this->render('admin/painting/painting_add_one.html.twig', ['painting' => $painting, 'form' => $form->createView()]);
    }

    /**
     * @Route("/edit-one/{id}/edit-from-dashboard", name="edit")
     */
    public function editPaintingDashboard(Request $request, Painting $painting)
    {
        $form = $this->createForm(PaintingEditType::class, $painting);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $this->em->persist($painting);
            $this->em->flush();

            $translated = $this->translator->trans('The artwork %painting% has been correctly modified', ['%painting%' => $painting->getTitle()]);
            $this->addFlash('success', $translated);

            return $this->redirectToRoute('admin_painting_list', ['_locale' => $request->getLocale()]);
        }

        return $this->render('admin/painting/painting_edit_one.html.twig', ['painting' => $painting, 'form' => $form->createView()]);
    }

    /**
     * @Route("/delete-one/{id}/delete-from-dashboard", name="delete")
     */
    public function deletePaintingDashboard(Request $request, $id)
    {
        $painting = $this->em->getRepository(Painting::class)->find($id);
        $this->em->remove($painting);
        $this->em->flush();

        $translated = $this->translator->trans('The artwork %painting% has been properly deleted', ['%painting%' => $painting->getTitle()]);
        $this->addFlash('success', $translated);

        return $this->redirectToRoute('admin_painting_list', ['_locale' => $request->getLocale()]);
    }

}
