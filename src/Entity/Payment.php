<?php

namespace App\Entity;

use App\Repository\PaymentRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PaymentRepository::class)
 */
class Payment
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $addressBilling;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $addressDelivery;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $cardNumber;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $cardType;

    /**
     * @ORM\Column(type="string", length=3)
     */
    private $crypto;

    /**
     * @ORM\Column(type="date")
     */
    private $dateExpiration;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $fullName;

    /**
     * @ORM\OneToOne(targetEntity=Booking::class, inversedBy="payment", cascade={"persist", "remove"})
     */
    private $booking;

    /**
     * @ORM\OneToOne(targetEntity=SavedPayment::class, inversedBy="payment", cascade={"persist", "remove"})
     */
    private $savedPayment;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAddressBilling(): ?string
    {
        return $this->addressBilling;
    }

    public function setAddressBilling(string $addressBilling): self
    {
        $this->addressBilling = $addressBilling;

        return $this;
    }

    public function getAddressDelivery(): ?string
    {
        return $this->addressDelivery;
    }

    public function setAddressDelivery(?string $addressDelivery): self
    {
        $this->addressDelivery = $addressDelivery;

        return $this;
    }

    public function getCardNumber(): ?string
    {
        return $this->cardNumber;
    }

    public function setCardNumber(string $cardNumber): self
    {
        $this->cardNumber = $cardNumber;

        return $this;
    }

    public function getCardType(): ?string
    {
        return $this->cardType;
    }

    public function setCardType(string $cardType): self
    {
        $this->cardType = $cardType;

        return $this;
    }

    public function getCrypto(): ?string
    {
        return $this->crypto;
    }

    public function setCrypto(string $crypto): self
    {
        $this->crypto = $crypto;

        return $this;
    }

    public function getDateExpiration(): ?\DateTimeInterface
    {
        return $this->dateExpiration;
    }

    public function setDateExpiration(\DateTimeInterface $dateExpiration): self
    {
        $this->dateExpiration = $dateExpiration;

        return $this;
    }

    public function getFullName(): ?string
    {
        return $this->fullName;
    }

    public function setFullName(string $fullName): self
    {
        $this->fullName = $fullName;

        return $this;
    }

    public function getBooking(): ?Booking
    {
        return $this->booking;
    }

    public function setBooking(?Booking $booking): self
    {
        $this->booking = $booking;

        return $this;
    }

    public function getSavedPayment(): ?SavedPayment
    {
        return $this->savedPayment;
    }

    public function setSavedPayment(?SavedPayment $savedPayment): self
    {
        $this->savedPayment = $savedPayment;

        return $this;
    }
}