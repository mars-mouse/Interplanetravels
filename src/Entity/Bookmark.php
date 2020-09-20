<?php

namespace App\Entity;

use App\Repository\BookmarkRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=BookmarkRepository::class)
 */
class Bookmark
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="boolean")
     */
    private $alert;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="bookmarks")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity=Travel::class, inversedBy="bookmarks")
     * @ORM\JoinColumn(nullable=false)
     */
    private $travel;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAlert(): ?bool
    {
        return $this->alert;
    }

    public function setAlert(bool $alert): self
    {
        $this->alert = $alert;

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
