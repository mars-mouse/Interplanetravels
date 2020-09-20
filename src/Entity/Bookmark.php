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
}
