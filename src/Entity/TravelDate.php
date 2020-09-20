<?php

namespace App\Entity;

use App\Repository\TravelDateRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TravelDateRepository::class)
 */
class TravelDate
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
    private $dateDeparture;

    /**
     * @ORM\Column(type="datetime")
     */
    private $dateReturn;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateDeparture(): ?\DateTimeInterface
    {
        return $this->dateDeparture;
    }

    public function setDateDeparture(\DateTimeInterface $dateDeparture): self
    {
        $this->dateDeparture = $dateDeparture;

        return $this;
    }

    public function getDateReturn(): ?\DateTimeInterface
    {
        return $this->dateReturn;
    }

    public function setDateReturn(\DateTimeInterface $dateReturn): self
    {
        $this->dateReturn = $dateReturn;

        return $this;
    }
}
