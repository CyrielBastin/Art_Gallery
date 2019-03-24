<?php

namespace App\Controller;

use App\Entity\Artist;
use App\Entity\Painting;
use App\Form\ArtistEditType;
use App\Form\ArtistType;
use App\Repository\ArtistRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
/**
 * @Route("/{_locale}/artist", name="artist_", requirements={"_locale"="%app.locales%"})
 */
class ArtistController extends AbstractController
{
    /**
     * @var EntityManagerInterface
     */
    private $em;
    /**
     * @var ArtistRepository
     */
    private $artistRepo;
    /**
     * @var TranslatorInterface
     */
    private $translator;

    public function __construct(EntityManagerInterface $em, ArtistRepository $artistRepo, TranslatorInterface $translator)
    {
        $this->em = $em;
        $this->artistRepo = $artistRepo;
        $this->translator = $translator;
    }


    /**
     * @Route("/", name="all")
     */
    public function viewArtists()
    {
        $artists = $this->artistRepo->findAll();

        return $this->render('artist/artist.html.twig', ['artists' => $artists]);
    }

    /**
     * @Route("/view/{id}", name="view_one")
     */
    public function viewOneArtist($id)
    {
        $artist = $this->artistRepo->findById($id);
        $paintings = $this->getDoctrine()->getRepository(Painting::class)->findByArtist($id);

        return $this->render('artist/artist_view_one.html.twig', ['artist' => $artist, 'paintings' => $paintings]);
    }

    /******************************************************************************************************************
     * Access denied unless granted below
     *****************************************************************************************************************/

    /**
     * @Route("/add", name="add")
     */
    public function addOneArtist(Request $request)
    {
        $artist = new Artist();
        $form = $this->createForm(ArtistType::class, $artist);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $this->em->persist($artist);
            $this->em->flush();

            $translated = $this->translator->trans('Artist %artist% successfully added', ['%artist%' => $artist->getArtist()]);
            $this->addFlash('success', $translated);

            return $this->redirectToRoute('homepage', ['_locale' => $request->getLocale()]);
        }

        return $this->render('artist/artist_add.html.twig', ['artist' => $artist, 'form' => $form->createView()]);
    }

    /**
     * @Route("/edit/{id}", name="edit_one")
     */
    public function editOneArtist(Request $request, Artist $artist)
    {
        $form = $this->createForm(ArtistEditType::class, $artist);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $this->em->persist($artist);
            $this->em->flush();

            $translated = $this->translator->trans('Artist %artist% successfully modified', ['%artist%' => $artist->getArtist()]);
            $this->addFlash('success', $translated);

            return $this->redirectToRoute('homepage', ['_locale' => $request->getLocale()]);
        }
        return $this->render('artist/artist_edit.html.twig', ['artist' => $artist, 'form' => $form->createView()]);
    }

    /**
     * @Route("/delete/{id}", name="delete_one")
     */
    public function deleteOneArtist(Request $request, $id)
    {
        $repository = $this->getDoctrine()->getRepository(Artist::class);
        $artist = $repository->find($id);
        $this->em->remove($artist);
        $this->em->flush();

        $translated = $this->translator->trans('Artist %artist% successfully removed', ['%artist%' => $artist->getArtist()]);
        $this->addFlash('success', $translated);

        return $this->redirectToRoute('homepage', ['_locale' => $request->getLocale()]);
    }
}
