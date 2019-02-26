<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PaintingRepository")
 */
class Painting
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $image;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $title;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $dimensions;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $year;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $descripition;

    /**
     * @ORM\Column(type="integer")
     */
    private $price;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\PaintingDiscount", mappedBy="painting", cascade={"persist", "remove"})
     */
    private $discount;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Artist", inversedBy="paintings")
     */
    private $artist;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\PaintingMedia", inversedBy="paintings")
     */
    private $media;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\PaintingStyle", inversedBy="paintings")
     */
    private $style;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getDimensions(): ?string
    {
        return $this->dimensions;
    }

    public function setDimensions(?string $dimensions): self
    {
        $this->dimensions = $dimensions;

        return $this;
    }

    public function getYear(): ?\DateTimeInterface
    {
        return $this->year;
    }

    public function setYear(?\DateTimeInterface $year): self
    {
        $this->year = $year;

        return $this;
    }

    public function getDescripition(): ?string
    {
        return $this->descripition;
    }

    public function setDescripition(?string $descripition): self
    {
        $this->descripition = $descripition;

        return $this;
    }

    public function getPrice(): ?int
    {
        return $this->price;
    }

    public function setPrice(int $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getDiscount(): ?PaintingDiscount
    {
        return $this->discount;
    }

    public function setDiscount(PaintingDiscount $discount): self
    {
        $this->discount = $discount;

        // set the owning side of the relation if necessary
        if ($this !== $discount->getPaintingId()) {
            $discount->setPaintingId($this);
        }

        return $this;
    }

    public function getArtist(): ?Artist
    {
        return $this->artist;
    }

    public function setArtist(?Artist $artist): self
    {
        $this->artist = $artist;

        return $this;
    }

    public function getMedia(): ?PaintingMedia
    {
        return $this->media;
    }

    public function setMedia(?PaintingMedia $media): self
    {
        $this->media = $media;

        return $this;
    }

    public function getStyle(): ?PaintingStyle
    {
        return $this->style;
    }

    public function setStyle(?PaintingStyle $style): self
    {
        $this->style = $style;

        return $this;
    }
}
