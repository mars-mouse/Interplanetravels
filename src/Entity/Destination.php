<?php

namespace App\Entity;

use App\Repository\DestinationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=DestinationRepository::class)
 */
class Destination
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(type="integer")
     */
    private $distance;

    /**
     * @ORM\Column(type="string", length=2)
     */
    private $distanceUnit;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\OneToMany(targetEntity=ImageDestination::class, mappedBy="destination")
     */
    private $images;

    /**
     * @ORM\ManyToOne(targetEntity=DestinationType::class, inversedBy="destinations")
     * @ORM\JoinColumn(nullable=false)
     */
    private $destinationType;

    /**
     * @ORM\OneToMany(targetEntity=Itinerary::class, mappedBy="destination")
     */
    private $itineraries;

    public function __construct()
    {
        $this->images = new ArrayCollection();
        $this->itineraries = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getDistance(): ?int
    {
        return $this->distance;
    }

    public function setDistance(int $distance): self
    {
        $this->distance = $distance;

        return $this;
    }

    public function getDistanceUnit(): ?string
    {
        return $this->distanceUnit;
    }

    public function setDistanceUnit(string $distanceUnit): self
    {
        $this->distanceUnit = $distanceUnit;

        return $this;
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

    /**
     * @return Collection|ImageDestination[]
     */
    public function getImages(): Collection
    {
        return $this->images;
    }

    public function addImage(ImageDestination $image): self
    {
        if (!$this->images->contains($image)) {
            $this->images[] = $image;
            $image->setDestination($this);
        }

        return $this;
    }

    public function removeImage(ImageDestination $image): self
    {
        if ($this->images->contains($image)) {
            $this->images->removeElement($image);
            // set the owning side to null (unless already changed)
            if ($image->getDestination() === $this) {
                $image->setDestination(null);
            }
        }

        return $this;
    }

    public function getDestinationType(): ?DestinationType
    {
        return $this->destinationType;
    }

    public function setDestinationType(?DestinationType $destinationType): self
    {
        $this->destinationType = $destinationType;

        return $this;
    }

    /**
     * @return Collection|Itinerary[]
     */
    public function getItineraries(): Collection
    {
        return $this->itineraries;
    }

    public function addItinerary(Itinerary $itinerary): self
    {
        if (!$this->itineraries->contains($itinerary)) {
            $this->itineraries[] = $itinerary;
            $itinerary->setDestination($this);
        }

        return $this;
    }

    public function removeItinerary(Itinerary $itinerary): self
    {
        if ($this->itineraries->contains($itinerary)) {
            $this->itineraries->removeElement($itinerary);
            // set the owning side to null (unless already changed)
            if ($itinerary->getDestination() === $this) {
                $itinerary->setDestination(null);
            }
        }

        return $this;
    }
}
