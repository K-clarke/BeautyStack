<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 */
class User implements UserInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @Serializer\Groups({"READ"})
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @Serializer\Groups({"READ","POST"})
     * @ORM\Column(type="string", length=255)
     */
    private $firstName;

    /**
     * @Serializer\Groups({"READ","POST"})
     * @ORM\Column(type="string", length=255)
     */
    private $lastName;

    /**
     * @Serializer\Groups({"READ","POST"})
     * @ORM\Column(type="string", length=255)
     * @Assert\Email(message="Please Enter A Valid Email Address")
     */
    private $email;

    /**
     * @Serializer\Groups({"POST"})
     * @Serializer\SerializedName("password")
     */
    private $pass;

    /** @ORM\Column(type="string", length=255) */
    private $password;

    /**
     * @Serializer\Groups({"READ","POST"})
     * @ORM\Column(type="array")
     */
    private $roles = ['ROLE_CLIENT'];

    /** @ORM\OneToMany(targetEntity="App\Entity\Style", mappedBy="uploadedBy") */
    private $styles;

    /** @ORM\OneToMany(targetEntity="App\Entity\Booking", mappedBy="user", orphanRemoval=true) */
    private $bookings;

    public function __construct()
    {
        $this->styles = new ArrayCollection();
        $this->bookings = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getPass()
    {
        return $this->pass;
    }

    public function setPass($pass): void
    {
        $this->pass = $pass;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getRoles()
    {
        return $this->roles;
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    public function getSalt()
    {
    }

    public function getUsername()
    {
        return $this->getEmail();
    }

    public function eraseCredentials()
    {
    }

    /**
     * @return Collection|Style[]
     */
    public function getStyles(): Collection
    {
        return $this->styles;
    }

    public function addStyle(Style $style): self
    {
        if (!$this->styles->contains($style)) {
            $this->styles[] = $style;
            $style->setUploadedBy($this);
        }

        return $this;
    }

    public function removeStyle(Style $style): self
    {
        if ($this->styles->contains($style)) {
            $this->styles->removeElement($style);
            // set the owning side to null (unless already changed)
            if ($style->getUploadedBy() === $this) {
                $style->setUploadedBy(null);
            }
        }

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
            $booking->setUser($this);
        }

        return $this;
    }

    public function removeBooking(Booking $booking): self
    {
        if ($this->bookings->contains($booking)) {
            $this->bookings->removeElement($booking);
            // set the owning side to null (unless already changed)
            if ($booking->getUser() === $this) {
                $booking->setUser(null);
            }
        }

        return $this;
    }
}
