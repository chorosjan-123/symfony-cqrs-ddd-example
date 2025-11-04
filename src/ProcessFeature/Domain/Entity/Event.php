<?php

namespace App\ProcessFeature\Domain\Entity;

use App\ProcessFeature\Infrastructure\Repository\EventRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EventRepository::class)]
class Event
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: 'integer')]
    private ?int $processId = null;

    #[ORM\Column(length: 255)]
    private ?string $processTitle = null;

    #[ORM\Column(length: 32)]
    private ?string $action = null;

    #[ORM\Column]
    private ?\DateTime $createdAt = null;

    public function __construct()
    {
         $this->createdAt = new \DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): static
    {
        $this->id = $id;

        return $this;
    }

    public function getProcessId(): ?int
    {
        return $this->processId;
    }

    public function setProcessId(?string $processId): static
    {
        $this->processId = $processId;

        return $this;
    }

    public function getProcessTitle(): ?string
    {
        return $this->processTitle;
    }

    public function setProcessTitle(?string $processTitle): static
    {
        $this->processTitle = $processTitle;

        return $this;
    }

    public function getAction(): ?string
    {
        return $this->action;
    }

    public function setAction(string $action): static
    {
        $this->action = $action;

        return $this;
    }

    public function getCreatedAt(): ?\DateTime
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTime $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    #[ORM\PrePersist()]
    public function setCreatedAtValue(): void
    {
        $this->createdAt = new \DateTime();
    }
}
