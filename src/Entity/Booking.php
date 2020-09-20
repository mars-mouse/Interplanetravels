<?php

namespace App\Entity;

use App\Repository\BookingRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=BookingRepository::class)
 */
class Booking
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     */
    private $date;

    /**
     * @ORM\Column(type="integer")
     */
    private $paidAmount;

    /**
     * @ORM\Column(type="float")
     */
    private $promotionValue;

    /**
     * @ORM\Column(type="integer")
     */
    private $numberPlaces;

    /**
     * @ORM\Column(type="boolean")
     */
    private $validated;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="bookings")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\OneToOne(targetEntity=Payment::class, mappedBy="booking", cascade={"persist", "remove"})
     */
    private $payment;

    /**
     * @ORM\ManyToOne(targetEntity=TravelDate::class, inversedBy="bookings")
     * @ORM\JoinColumn(nullable=false)
     */
    private $travelDate;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getPaidAmount(): ?int
    {
        return $this->paidAmount;
    }

    public function setPaidAmount(int $paidAmount): self
    {
        $this->paidAmount = $paidAmount;

        return $this;
    }

    public function getPromotionValue(): ?float
    {
        return $this->promotionValue;
    }

    public function setPromotionValue(float $promotionValue): self
    {
        $this->promotionValue = $promotionValue;

        return $this;
    }

    public function getNumberPlaces(): ?int
    {
        return $this->numberPlaces;
    }

    public function setNumberPlaces(int $numberPlaces): self
    {
        $this->numberPlaces = $numberPlaces;

        return $this;
    }

    public function getValidated(): ?bool
    {
        return $this->validated;
    }

    public function setValidated(bool $validated): self
    {
        $this->validated = $validated;

        return $this;
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

    public function getPayment(): ?Payment
    {
        return $this->payment;
    }

    public function setPayment(?Payment $payment): self
    {
        $this->payment = $payment;

        // set (or unset) the owning side of the relation if necessary
        $newBooking = null === $payment ? null : $this;
        if ($payment->getBooking() !== $newBooking) {
            $payment->setBooking($newBooking);
        }

        return $this;
    }

    public function getTravelDate(): ?TravelDate
    {
        return $this->travelDate;
    }

    public function setTravelDate(?TravelDate $travelDate): self
    {
        $this->travelDate = $travelDate;

        return $this;
    }
}