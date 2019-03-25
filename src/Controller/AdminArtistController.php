<?php

namespace App\Controller;


use App\Entity\Artist;
use App\Form\ArtistEditType;
use App\Form\ArtistType;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * @Route("/{_locale}/admin/artists", name="admin_artist_", requirements={"_locale"="%app.locales%"})
 */
class AdminArtistController extends AbstractController
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
    public function showListArtists(Request $request, PaginatorInterface $paginator)
    {
        $artists = $this->getDoctrine()->getRepository(Artist::class)->adminListArtist();

        $pagination = $paginator->paginate($artists, $request->query->getInt('page', 1), 12);

        return $this->render('admin/artist/artist_list.html.twig', ['artists' => $pagination]);
    }

    /**
     * @Route("/add-one/add-from-dashboard", name="add")
     */
    public function addArtistDashboard(Request $request)
    {
        $artist = new Artist();
        $form = $this->createForm(ArtistType::class, $artist);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $this->em->persist($artist);
            $this->em->flush();

            $translated = $this->translator->trans('Artist %artist% successfully added', ['%artist%' => $artist->getArtist()]);
            $this->addFlash('success', $translated);

            return $this->redirectToRoute('admin_artist_list', ['_locale' => $request->getLocale()]);
        }

        return $this->render('admin/artist/artist_add_one.html.twig', ['artist' => $artist, 'form' => $form->createView()]);
    }

    /**
     * @Route("/edit-one/{id}/edit-from-dashboard", name="edit")
     */
    public function editArtistDashboard(Request $request, Artist $artist)
    {
        $form = $this->createForm(ArtistEditType::class, $artist);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $this->em->persist($artist);
            $this->em->flush();

            $translated = $this->translator->trans('Artist %artist% successfully modified', ['%artist%' => $artist->getArtist()]);
            $this->addFlash('success', $translated);

            return $this->redirectToRoute('admin_artist_list', ['_locale' => $request->getLocale()]);
        }

        return $this->render('admin/artist/artist_edit_one.html.twig', ['artist' => $artist, 'form' => $form->createView()]);
    }

    /**
     * @Route("/delete-one/{id}/delete-from-dashboard", name="delete")
     */
    public function deleteArtistDashboard(Request $request, $id)
    {
        $artist = $this->em->getRepository(Artist::class)->find($id);
        $this->em->remove($artist);
        $this->em->flush();

        $translated = $this->translator->trans('Artist %artist% successfully removed', ['%artist%' => $artist->getArtist()]);
        $this->addFlash('success', $translated);

        return $this->redirectToRoute('admin_artist_list', ['_locale' => $request->getLocale()]);
    }

}
