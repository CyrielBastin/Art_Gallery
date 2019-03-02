<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PaintingStyleRepository")
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
     * @ORM\Column(type="string", length=100)
     */
    private $name;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Painting", mappedBy="style")
     */
    private $paintings;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $image;

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

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): self
    {
        $this->image = $image;

        return $this;
    }
}
