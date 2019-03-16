<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PaintingRepository")
 * @ORM\EntityListeners({"App\EventListener\PaintingListener"})
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
     * @ORM\Column(type="string", length=20, nullable=true)
     * @Assert\Regex("#[0-9]{2,3}\sX\s[0-9]{2,3}#i")
     */
    private $dimensions;

    /**
     * @ORM\Column(type="string", length=5, nullable=true)
     * @Assert\Range(
     *     min = -1000,
     *     max = 2019,
     *     maxMessage = "The artwork can't be painted in the future"
     * )
     */
    private $year;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @Assert\Length(
     *     min = 10,
     *     max = 2000,
     *     minMessage = "It would be nice to write a little comment about the artwork ({{ limit }} minimum letters please)",
     *     maxMessage = "This place is for a little description, no need to write a novel !"
     * )
     */
    private $description;

    /**
     * @ORM\Column(type="integer")
     * @Assert\GreaterThanOrEqual(
     *     value = 0,
     *     message = "You can't put a price of less than {{ compared_value }} euros"
     * )
     */
    private $price;

    /**
     * @ORM\Column(type="smallint")
     * @Assert\Range(
     *     min = 0,
     *     max = 100,
     *     minMessage = "{{ limit }} % minimum for the discount",
     *     maxMessage = "You can't discount more than {{ limit }} %"
     * )
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

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\PaintingComment", mappedBy="painting", orphanRemoval=true)
     */
    private $paintingComments;

    public function __construct()
    {
        $this->paintingComments = new ArrayCollection();
    }

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

    public function getDiscount(): ?int
    {
        return $this->discount;
    }

    public function setDiscount(?int $discount): self
    {
        $this->discount = $discount;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updated_at;
    }

    public function setUpdatedAt(?\DateTimeInterface $updated_at): self
    {
        $this->updated_at = $updated_at;

        return $this;
    }

    /**
     * @return Collection|PaintingComment[]
     */
    public function getPaintingComments(): Collection
    {
        return $this->paintingComments;
    }

    public function addPaintingComment(PaintingComment $paintingComment): self
    {
        if (!$this->paintingComments->contains($paintingComment)) {
            $this->paintingComments[] = $paintingComment;
            $paintingComment->setPainting($this);
        }

        return $this;
    }

    public function removePaintingComment(PaintingComment $paintingComment): self
    {
        if ($this->paintingComments->contains($paintingComment)) {
            $this->paintingComments->removeElement($paintingComment);
            // set the owning side to null (unless already changed)
            if ($paintingComment->getPainting() === $this) {
                $paintingComment->setPainting(null);
            }
        }

        return $this;
    }

}
