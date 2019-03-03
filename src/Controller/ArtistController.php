<?php

namespace App\Controller;

use App\Entity\Artist;
use App\Entity\Painting;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
     * @Route("view/{id}", name="view_one")
     */
    public function viewOneArtist($id)
    {
        $artist = $this->getDoctrine()->getRepository(Artist::class)->findById($id);
        $paintings = $this->getDoctrine()->getRepository(Painting::class)->findByArtist($id);

        return $this->render('artist/artist_view_one.html.twig', ['artist' => $artist, 'paintings' => $paintings]);
    }
}
