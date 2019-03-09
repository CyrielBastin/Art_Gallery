<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PaintingStyleRepository")
 * @UniqueEntity("name")
 * @Vich\Uploadable()
 */
class PaintingStyle
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=25)
     * @Assert\Length(
     *     min = 4,
     *     max = 25,
     *     minMessage = "The name of the media must contain at leat {{ limit }} caracters",
     *     maxMessage = "{{ limit }} caracters maximum"
     * )
     */
    private $name;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @Assert\Length(
     *   min = 10,
     *   max = 2000,
     *   minMessage = "It would be nice to write a little description about the media ({{ limit }} letters please)",
     *   maxMessage = "This place is for a description, no need to write a novel !"
     * )
     */
    private $description;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Painting", mappedBy="style")
     */
    private $paintings;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $image;

    /**
     * @Assert\Image(
     *     mimeTypes={"image/jpg", "image/jpeg", "image/png"}
     * )
     * @Vich\UploadableField(mapping="style_image", fileNameProperty="image")
     * @var File
     */
    private $imageFile;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updated_at;

    public function __construct()
    {
        $this->paintings = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

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

    /**
     * @return Collection|Painting[]
     */
    public function getPaintings(): Collection
    {
        return $this->paintings;
    }

    public function addPainting(Painting $painting): self
    {
        if (!$this->paintings->contains($painting)) {
            $this->paintings[] = $painting;
            $painting->setStyle($this);
        }

        return $this;
    }

    public function removePainting(Painting $painting): self
    {
        if ($this->paintings->contains($painting)) {
            $this->paintings->removeElement($painting);
            // set the owning side to null (unless already changed)
            if ($painting->getStyle() === $this) {
                $painting->setStyle(null);
            }
        }

        return $this;
    }

    public function getStyle()
    {
        return $this->getName();
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
