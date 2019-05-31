<?php

declare(strict_types=1);

namespace App\Entity;

use App\Validator\CreatedByPro;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\BookingRepository")
 */
class Booking
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="bookings")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Style", inversedBy="bookings")
     * @ORM\JoinColumn(nullable=false)
     * @CreatedByPro()
     */
    private $style;

    /**
     * @ORM\Column(type="datetime")
     * @Assert\DateTime()
     */
    private $timeSlot;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getStyle(): ?Style
    {
        return $this->style;
    }

    public function setStyle(?Style $style): self
    {
        $this->style = $style;

        return $this;
    }

    public function getTimeSlot(): ?\DateInterval
    {
        return $this->timeSlot;
    }

    public function setTimeSlot(\DateTime $timeSlot): self
    {
        $this->timeSlot = $timeSlot;

        return $this;
    }
}
