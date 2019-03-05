<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PaintingRepository")
 * @Vich\Uploadable
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
     * @var string
     */
    private $image;

    /**
     * @Assert\Image(
     *     mimeTypes={"image/jpg", "image/jpeg", "image/png"}
     * )
     * @Vich\UploadableField(mapping="painting_image", fileNameProperty="image")
     * @var File
     */
    private $imageFile;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Length(
     *     min = 4,
     *     max = 50,
     *     minMessage = "The title must be of at least {{ limit }} characters",
     *     maxMessage = "The title must be of {{ limit }} characters at max"
     * )
     */
    private $title;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $dimensions;

    /**
     * @ORM\Column(type="string", length=5, nullable=true)
     */
    private $year;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

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

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updated_at;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getYear(): ?string
    {
        return $this->year;
    }

    public function setYear(?string $year): self
    {
        $this->year = $year;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

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

    public function setImageFile(File $image = null)
    {
        $this->imageFile = $image;

        if ($image) {
            try {
                $this->updated_at = new \DateTime('now');
            } catch (\Exception $e) {}
        }
    }

    public function getImageFile()
    {
        return $this->imageFile;
    }

    public function setImage($image)
    {
        $this->image = $image;
    }

    public function getImage()
    {
        return $this->image;
    }

}
