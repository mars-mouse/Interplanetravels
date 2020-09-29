<?php

namespace App\Entity;

use App\Repository\PromotionRepository;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PromotionRepository::class)
 */
class Promotion
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
    private $amount;    // percentage off

    /**
     * @ORM\Column(type="datetime")
     */
    private $dateStart;

    /**
     * @ORM\Column(type="datetime")
     */
    private $dateEnd;

    /**
     * @ORM\OneToMany(targetEntity=Travel::class, mappedBy="promotion")
     */
    private $travels;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $title;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    public function __construct()
    {
        $this->travels = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAmount(): ?int
    {
        return $this->amount;
    }

    public function setAmount(int $amount): self
    {
        $this->amount = $amount;

        return $this;
    }

    public function getDateStart(): ?\DateTimeInterface
    {
        return $this->dateStart;
    }

    public function setDateStart(\DateTimeInterface $dateStart): self
    {
        $this->dateStart = $dateStart;

        return $this;
    }

    public function getDateEnd(): ?\DateTimeInterface
    {
        return $this->dateEnd;
    }

    public function setDateEnd(\DateTimeInterface $dateEnd): self
    {
        $this->dateEnd = $dateEnd;

        return $this;
    }

    /**
     * @return Collection|Travel[]
     */
    public function getTravels(): Collection
    {
        return $this->travels;
    }

    public function addTravel(Travel $travel): self
    {
        if (!$this->travels->contains($travel)) {
            $this->travels[] = $travel;
            $travel->setPromotion($this);
        }

        return $this;
    }

    public function removeTravel(Travel $travel): self
    {
        if ($this->travels->contains($travel)) {
            $this->travels->removeElement($travel);
            // set the owning side to null (unless already changed)
            if ($travel->getPromotion() === $this) {
                $travel->setPromotion(null);
            }
        }

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

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

    public function isPromotionActive()
    {
        $currentDate = new DateTime('now');

        if ($this->dateStart <= $currentDate && $currentDate < $this->dateEnd) {
            return true;
        }
        return false;
    }
}