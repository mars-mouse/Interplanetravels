<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TravelRepository::class)
 */
class Travel
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $maxPlaces;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="integer")
     */
    private $price;

    /**
     * @ORM\OneToMany(targetEntity=TravelDate::class, mappedBy="travel")
     */
    private $travelDates;

    /**
     * @ORM\OneToMany(targetEntity=Itinerary::class, mappedBy="travel")
     */
    private $itineraries;

    /**
     * @ORM\OneToMany(targetEntity=Review::class, mappedBy="travel")
     */
    private $reviews;

    /**
     * @ORM\ManyToOne(targetEntity=Promotion::class, inversedBy="travels")
     */
    private $promotion;

    /**
     * @ORM\ManyToOne(targetEntity=DepartFrom::class, inversedBy="travels")
     * @ORM\JoinColumn(nullable=false)
     */
    private $departFrom;

    /**
     * @ORM\OneToMany(targetEntity=ImageTravel::class, mappedBy="travel")
     */
    private $images;

    /**
     * @ORM\OneToMany(targetEntity=Bookmark::class, mappedBy="travel")
     */
    private $bookmarks;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    public function __construct()
    {
        $this->travelDates = new ArrayCollection();
        $this->itineraries = new ArrayCollection();
        $this->reviews = new ArrayCollection();
        $this->images = new ArrayCollection();
        $this->bookmarks = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMaxPlaces(): ?int
    {
        return $this->maxPlaces;
    }

    public function setMaxPlaces(?int $maxPlaces): self
    {
        $this->maxPlaces = $maxPlaces;

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

    public function getPrice(): ?int
    {
        return $this->price;
    }

    public function setPrice(int $price): self
    {
        $this->price = $price;

        return $this;
    }

    /**
     * @return Collection|TravelDate[]
     */
    public function getTravelDates(): Collection
    {
        return $this->travelDates;
    }

    public function addTravelDate(TravelDate $travelDate): self
    {
        if (!$this->travelDates->contains($travelDate)) {
            $this->travelDates[] = $travelDate;
            $travelDate->setTravel($this);
        }

        return $this;
    }

    public function removeTravelDate(TravelDate $travelDate): self
    {
        if ($this->travelDates->contains($travelDate)) {
            $this->travelDates->removeElement($travelDate);
            // set the owning side to null (unless already changed)
            if ($travelDate->getTravel() === $this) {
                $travelDate->setTravel(null);
            }
        }

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
            $itinerary->setTravel($this);
        }

        return $this;
    }

    public function removeItinerary(Itinerary $itinerary): self
    {
        if ($this->itineraries->contains($itinerary)) {
            $this->itineraries->removeElement($itinerary);
            // set the owning side to null (unless already changed)
            if ($itinerary->getTravel() === $this) {
                $itinerary->setTravel(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Review[]
     */
    public function getReviews(): Collection
    {
        return $this->reviews;
    }

    public function addReview(Review $review): self
    {
        if (!$this->reviews->contains($review)) {
            $this->reviews[] = $review;
            $review->setTravel($this);
        }

        return $this;
    }

    public function removeReview(Review $review): self
    {
        if ($this->reviews->contains($review)) {
            $this->reviews->removeElement($review);
            // set the owning side to null (unless already changed)
            if ($review->getTravel() === $this) {
                $review->setTravel(null);
            }
        }

        return $this;
    }

    public function getPromotion(): ?Promotion
    {
        return $this->promotion;
    }

    public function setPromotion(?Promotion $promotion): self
    {
        $this->promotion = $promotion;

        return $this;
    }

    public function getDepartFrom(): ?DepartFrom
    {
        return $this->departFrom;
    }

    public function setDepartFrom(?DepartFrom $departFrom): self
    {
        $this->departFrom = $departFrom;

        return $this;
    }

    /**
     * @return Collection|ImageTravel[]
     */
    public function getImages(): Collection
    {
        return $this->images;
    }

    public function addImage(ImageTravel $image): self
    {
        if (!$this->images->contains($image)) {
            $this->images[] = $image;
            $image->setTravel($this);
        }

        return $this;
    }

    public function removeImage(ImageTravel $image): self
    {
        if ($this->images->contains($image)) {
            $this->images->removeElement($image);
            // set the owning side to null (unless already changed)
            if ($image->getTravel() === $this) {
                $image->setTravel(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Bookmark[]
     */
    public function getBookmarks(): Collection
    {
        return $this->bookmarks;
    }

    public function addBookmark(Bookmark $bookmark): self
    {
        if (!$this->bookmarks->contains($bookmark)) {
            $this->bookmarks[] = $bookmark;
            $bookmark->setTravel($this);
        }

        return $this;
    }

    public function removeBookmark(Bookmark $bookmark): self
    {
        if ($this->bookmarks->contains($bookmark)) {
            $this->bookmarks->removeElement($bookmark);
            // set the owning side to null (unless already changed)
            if ($bookmark->getTravel() === $this) {
                $bookmark->setTravel(null);
            }
        }

        return $this;
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
}