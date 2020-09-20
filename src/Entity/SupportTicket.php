<?php

namespace App\Entity;

use App\Repository\SupportTicketRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=SupportTicketRepository::class)
 */
class SupportTicket
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
    private $email;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $questionTitle;

    /**
     * @ORM\Column(type="text")
     */
    private $questionFullText;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $visitorName;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="supportTickets")
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity=TicketCategory::class, inversedBy="supportTickets")
     * @ORM\JoinColumn(nullable=false)
     */
    private $ticketCategory;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getQuestionTitle(): ?string
    {
        return $this->questionTitle;
    }

    public function setQuestionTitle(string $questionTitle): self
    {
        $this->questionTitle = $questionTitle;

        return $this;
    }

    public function getQuestionFullText(): ?string
    {
        return $this->questionFullText;
    }

    public function setQuestionFullText(string $questionFullText): self
    {
        $this->questionFullText = $questionFullText;

        return $this;
    }

    public function getVisitorName(): ?string
    {
        return $this->visitorName;
    }

    public function setVisitorName(?string $visitorName): self
    {
        $this->visitorName = $visitorName;

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

    public function getTicketCategory(): ?TicketCategory
    {
        return $this->ticketCategory;
    }

    public function setTicketCategory(?TicketCategory $ticketCategory): self
    {
        $this->ticketCategory = $ticketCategory;

        return $this;
    }
}
