<?php

namespace App\Controller;

use App\Entity\Artist;
use App\Entity\Painting;
use App\Form\ArtistEditType;
use App\Form\ArtistType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/artist", name="artist_")
 */
class ArtistController extends AbstractController
{
    /**
     * @Route("/", name="all")
     */
    public function viewArtists()
    {
        $artists = $this->getDoctrine()->getRepository(Artist::class)->findAll();

        return $this->render('artist/artist.html.twig', ['artists' => $artists]);
    }

    /**
     * @Route("/view/{id}", name="view_one")
     */
    public function viewOneArtist($id)
    {
        $artist = $this->getDoctrine()->getRepository(Artist::class)->findById($id);
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
            $em = $this->getDoctrine()->getManager();
            $em->persist($artist);
            $em->flush();

            $this->addFlash('success', 'Artist ' . $artist->getArtist() . ' successfully added');
            return $this->redirectToRoute('homepage');
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
            $em = $this->getDoctrine()->getManager();
            $em->persist($artist);
            $em->flush();

            $this->addFlash('success', 'Artist ' . $artist->getArtist() . ' successfully modified');
            return $this->redirectToRoute('homepage');
        }
        return $this->render('artist/artist_edit.html.twig', ['artist' => $artist, 'form' => $form->createView()]);
    }

    /**
     * @Route("/delete/{id}", name="delete_one")
     */
    public function deleteOneArtist($id)
    {
        $em = $this->getDoctrine()->getManager();
        $repository = $this->getDoctrine()->getRepository(Artist::class);
        $artist = $repository->find($id);
        $em->remove($artist);
        $em->flush();

        $this->addFlash('success', 'Artist ' . $artist->getArtist() . ' successfully removed');
        return $this->redirectToRoute('homepage');
    }
}
