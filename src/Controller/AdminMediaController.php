<?php

namespace App\Controller;


use App\Entity\PaintingMedia;
use App\Form\PaintingMediaEditType;
use App\Form\PaintingMediaType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * @Route("/{_locale}/admin/medias", name="admin_media_", requirements={"_locale"="%app.locales%"})
 */
class AdminMediaController extends AbstractController
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
    public function showListMedias()
    {
        $medias = $this->getDoctrine()->getRepository(PaintingMedia::class)->adminListMedia();

        return $this->render('admin/media/media_list.html.twig', ['medias' => $medias]);
    }

    /**
     * @Route("/add-one/add-from-dashboard", name="add")
     */
    public function addMediaDashboard(Request $request)
    {
        $media = new PaintingMedia();
        $form = $this->createForm(PaintingMediaType::class, $media);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $this->em->persist($media);
            $this->em->flush();

            $translated = $this->translator->trans('Media %media% successfully added', ['%media%' => $media->getName()]);
            $this->addFlash('success', $translated);

            return $this->redirectToRoute('admin_media_list', ['_locale' => $request->getLocale()]);
        }

        return $this->render('admin/media/media_add_one.html.twig', ['media' => $media, 'form' => $form->createView()]);
    }

    /**
     * @Route("/edit-one/{id}/edit-from-dashboard", name="edit")
     */
    public function editMediaDashboard(Request $request, PaintingMedia $media)
    {
        $form = $this->createForm(PaintingMediaEditType::class, $media);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $this->em->persist($media);
            $this->em->flush();

            $translated = $this->translator->trans('Media %media% successfully modified', ['%media%' => $media->getName()]);
            $this->addFlash('success', $translated);

            return $this->redirectToRoute('admin_media_list', ['_locale' => $request->getLocale()]);
        }

        return $this->render('admin/media/media_edit_one.html.twig', ['media' => $media, 'form' => $form->createView()]);
    }

    /**
     * @Route("/delete-one/{id}/delete-from-dashboard", name="delete")
     */
    public function deleteMediaDashboard(Request $request, $id)
    {
        $media = $this->em->getRepository(PaintingMedia::class)->find($id);
        $this->em->remove($media);
        $this->em->flush();

        $translated = $this->translator->trans('Media %media% successfully removed', ['%media%' => $media->getName()]);
        $this->addFlash('success', $translated);

        return $this->redirectToRoute('admin_media_list', ['_locale' => $request->getLocale()]);
    }

}
