<?php

namespace App\Entity;

use App\Repository\TicketCategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TicketCategoryRepository::class)
 */
class TicketCategory
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
    private $name;

    /**
     * @ORM\OneToMany(targetEntity=SupportTicket::class, mappedBy="ticketCategory")
     */
    private $supportTickets;

    public function __construct()
    {
        $this->supportTickets = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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
     * @return Collection|SupportTicket[]
     */
    public function getSupportTickets(): Collection
    {
        return $this->supportTickets;
    }

    public function addSupportTicket(SupportTicket $supportTicket): self
    {
        if (!$this->supportTickets->contains($supportTicket)) {
            $this->supportTickets[] = $supportTicket;
            $supportTicket->setTicketCategory($this);
        }

        return $this;
    }

    public function removeSupportTicket(SupportTicket $supportTicket): self
    {
        if ($this->supportTickets->contains($supportTicket)) {
            $this->supportTickets->removeElement($supportTicket);
            // set the owning side to null (unless already changed)
            if ($supportTicket->getTicketCategory() === $this) {
                $supportTicket->setTicketCategory(null);
            }
        }

        return $this;
    }
}
