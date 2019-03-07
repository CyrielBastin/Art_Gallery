<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ArtistRepository")
 * @Vich\Uploadable()
 */
class Artist
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
     * @Assert\Image(
     *     mimeTypes={"image/jpg", "image/jpeg", "image/png"}
     * )
     * @Vich\UploadableField(mapping="artist_image", fileNameProperty="image")
     * @var File
     */
    private $imageFile;

    /**
     * @ORM\Column(type="string", length=100)
     * @Assert\Length(
     *     min = 4,
     *     max = 100,
     *     minMessage = "The author's lastname must contain at least {{ limit }} letters",
     *     maxMessage = "No more than {{ limit }} letters for the lastname"
     * )
     */
    private $lastname;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     * @Assert\Length(
     *     min = 4,
     *     max = 100,
     *     minMessage = "The author's lastname must contain at least {{ limit }} letters",
     *     maxMessage = "No more than {{ limit }} letters for the lastname"
     * )
     */
    private $firstname;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $country;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @Assert\Length(
     *     min = 10,
     *     max = 2000,
     *     minMessage = "It would be nice to write a little comment about the author ({{ limit }} minimum letters please)",
     *     maxMessage = "This place is for a description, no need to write a novel ! ( {{ limit }} letters max )"
     * )
     */
    private $commentary;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $date_of_birth;

    /**
     * @ORM\Column(type="date", nullable=true)
     * @Assert\Range(
     *     max = "now"
     * )
     */
    private $date_of_death;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Painting", mappedBy="artist")
     */
    private $paintings;

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

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): self
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(?string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getCountry(): ?string
    {
        return $this->country;
    }

    public function setCountry(?string $country): self
    {
        $this->country = $country;

        return $this;
    }

    public function getCommentary(): ?string
    {
        return $this->commentary;
    }

    public function setCommentary(?string $commentary): self
    {
        $this->commentary = $commentary;

        return $this;
    }

    public function getDateOfBirth(): ?\DateTimeInterface
    {
        return $this->date_of_birth;
    }

    public function setDateOfBirth(?\DateTimeInterface $date_of_birth): self
    {
        $this->date_of_birth = $date_of_birth;

        return $this;
    }

    public function getDateOfDeath(): ?\DateTimeInterface
    {
        return $this->date_of_death;
    }

    public function setDateOfDeath(?\DateTimeInterface $date_of_death): self
    {
        $this->date_of_death = $date_of_death;

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
            $painting->setArtist($this);
        }

        return $this;
    }

    public function removePainting(Painting $painting): self
    {
        if ($this->paintings->contains($painting)) {
            $this->paintings->removeElement($painting);
            // set the owning side to null (unless already changed)
            if ($painting->getArtist() === $this) {
                $painting->setArtist(null);
            }
        }

        return $this;
    }

    public function getArtist()
    {
        return $this->getLastname().' '.$this->getFirstname();
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
