<?php

namespace App\Entity;

use App\Repository\ItineraryRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ItineraryRepository::class)
 */
class Itinerary
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $dayArrival;

    /**
     * @ORM\Column(type="integer")
     */
    private $dayDeparture;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDayArrival(): ?\DateTimeInterface
    {
        return $this->dayArrival;
    }

    public function setDayArrival(\DateTimeInterface $dayArrival): self
    {
        $this->dayArrival = $dayArrival;

        return $this;
    }

    public function getDayDeparture(): ?\DateTimeInterface
    {
        return $this->dayDeparture;
    }

    public function setDayDeparture(\DateTimeInterface $dayDeparture): self
    {
        $this->dayDeparture = $dayDeparture;

        return $this;
    }
}