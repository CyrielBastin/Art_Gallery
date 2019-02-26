<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PaintingDiscountRepository")
 */
class PaintingDiscount
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="smallint")
     */
    private $discount;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Painting", inversedBy="discount", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false, onDelete="CASCADE")
     */
    private $painting;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDiscount(): ?int
    {
        return $this->discount;
    }

    public function setDiscount(int $discount): self
    {
        $this->discount = $discount;

        return $this;
    }

    public function getPaintingId(): ?Painting
    {
        return $this->painting;
    }

    public function setPaintingId(Painting $painting): self
    {
        $this->painting = $painting;

        return $this;
    }
}
