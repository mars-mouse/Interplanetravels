<?php

namespace App\Entity;

use Symfony\Component\Validator\Constraints as Assert;
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
     * @Assert\Expression(expression="this.getDayArrival <= value", message="The day of departure must come after the day of arrival.")
     */
    private $dayDeparture;

    /**
     * @ORM\ManyToOne(targetEntity=Destination::class, inversedBy="itineraries")
     * @ORM\JoinColumn(nullable=false)
     */
    private $destination;

    /**
     * @ORM\ManyToOne(targetEntity=Transport::class, inversedBy="itineraries")
     * @ORM\JoinColumn(nullable=false)
     */
    private $transport;

    /**
     * @ORM\ManyToOne(targetEntity=Travel::class, inversedBy="itineraries")
     * @ORM\JoinColumn(nullable=false)
     */
    private $travel;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDayArrival()
    {
        return $this->dayArrival;
    }

    public function setDayArrival(int $dayArrival): self
    {
        $this->dayArrival = $dayArrival;

        return $this;
    }

    public function getDayDeparture()
    {
        return $this->dayDeparture;
    }

    public function setDayDeparture(int $dayDeparture): self
    {
        $this->dayDeparture = $dayDeparture;

        return $this;
    }

    public function getDestination(): ?Destination
    {
        return $this->destination;
    }

    public function setDestination(?Destination $destination): self
    {
        $this->destination = $destination;

        return $this;
    }

    public function getTransport(): ?Transport
    {
        return $this->transport;
    }

    public function setTransport(?Transport $transport): self
    {
        $this->transport = $transport;

        return $this;
    }

    public function getTravel(): ?Travel
    {
        return $this->travel;
    }

    public function setTravel(?Travel $travel): self
    {
        $this->travel = $travel;

        return $this;
    }
}
