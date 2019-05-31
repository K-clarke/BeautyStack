<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\StyleRepository")
 * @Serializer\ExclusionPolicy("ALL")
 */
class Style
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="text")
     * @Serializer\Expose()
     */
    private $picture;

    /**
     * @ORM\Column(type="string", length=255)
     * @Serializer\Expose()
     */
    private $description;

    /**
     * @ORM\Column(type="float", length=255)
     * @Serializer\Expose()
     */
    private $price;

    /**
     * @ORM\Column(type="time")
     * @Assert\Time()
     * @Serializer\Expose()
     */
    private $time;

    /**
     * @Serializer\MaxDepth(2)
     * @ORM\ManyToMany(targetEntity="App\Entity\Tag", inversedBy="styles", cascade={"persist"})
     * @Serializer\Expose()
     */
    private $tag;

    /** @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="styles") */
    private $uploadedBy;

    /** @ORM\OneToMany(targetEntity="App\Entity\Booking", mappedBy="style", orphanRemoval=true) */
    private $bookings;

    public function __construct()
    {
        $this->tag = new ArrayCollection();
        $this->bookings = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPicture(): ?string
    {
        return $this->picture;
    }

    public function setPicture(string $picture): self
    {
        $this->picture = $picture;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(string $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getTime(): ?\DateTimeInterface
    {
        return $this->time;
    }

    public function setTime(\DateTimeInterface $time): self
    {
        $this->time = $time;

        return $this;
    }

    /**
     * @return Collection|Tag[]
     */
    public function getTag(): Collection
    {
        return $this->tag;
    }

    public function addTag(Tag $tag): self
    {
        if (!$this->tag->contains($tag)) {
            $this->tag[] = $tag;
        }

        return $this;
    }

    public function removeTag(Tag $tag): self
    {
        if ($this->tag->contains($tag)) {
            $this->tag->removeElement($tag);
        }

        return $this;
    }

    public function getUploadedBy(): ?User
    {
        return $this->uploadedBy;
    }

    public function setUploadedBy(?User $uploadedBy): self
    {
        $this->uploadedBy = $uploadedBy;

        return $this;
    }

    /**
     * @return Collection|Booking[]
     */
    public function getBookings(): Collection
    {
        return $this->bookings;
    }

    public function addBooking(Booking $booking): self
    {
        if (!$this->bookings->contains($booking)) {
            $this->bookings[] = $booking;
            $booking->setStyle($this);
        }

        return $this;
    }

    public function removeBooking(Booking $booking): self
    {
        if ($this->bookings->contains($booking)) {
            $this->bookings->removeElement($booking);
            if ($booking->getStyle() === $this) {
                $booking->setStyle(null);
            }
        }

        return $this;
    }
}
