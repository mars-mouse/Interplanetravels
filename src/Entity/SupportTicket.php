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
}
