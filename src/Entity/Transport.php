<?php

namespace App\Entity;

use App\Repository\TransportRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TransportRepository::class)
 */
class Transport
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
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\OneToMany(targetEntity=ImageTransport::class, mappedBy="transport")
     */
    private $images;

    /**
     * @ORM\ManyToOne(targetEntity=TransportType::class, inversedBy="transports")
     * @ORM\JoinColumn(nullable=false)
     */
    private $transportType;

    /**
     * @ORM\OneToMany(targetEntity=Itinerary::class, mappedBy="transport")
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
     * @return Collection|ImageTransport[]
     */
    public function getImages(): Collection
    {
        return $this->images;
    }

    public function addImage(ImageTransport $image): self
    {
        if (!$this->images->contains($image)) {
            $this->images[] = $image;
            $image->setTransport($this);
        }

        return $this;
    }

    public function removeImage(ImageTransport $image): self
    {
        if ($this->images->contains($image)) {
            $this->images->removeElement($image);
            // set the owning side to null (unless already changed)
            if ($image->getTransport() === $this) {
                $image->setTransport(null);
            }
        }

        return $this;
    }

    public function getTransportType(): ?TransportType
    {
        return $this->transportType;
    }

    public function setTransportType(?TransportType $transportType): self
    {
        $this->transportType = $transportType;

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
            $itinerary->setTransport($this);
        }

        return $this;
    }

    public function removeItinerary(Itinerary $itinerary): self
    {
        if ($this->itineraries->contains($itinerary)) {
            $this->itineraries->removeElement($itinerary);
            // set the owning side to null (unless already changed)
            if ($itinerary->getTransport() === $this) {
                $itinerary->setTransport(null);
            }
        }

        return $this;
    }
}
