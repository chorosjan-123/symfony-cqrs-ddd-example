<?php

namespace App\ProcessFeature\Domain\Entity;

use App\ProcessFeature\Domain\ValueObject\ProcessStatus;
use App\ProcessFeature\Infrastructure\Repository\ProcessRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[ORM\Entity(repositoryClass: ProcessRepository::class)]
#[ORM\HasLifecycleCallbacks]
#[UniqueEntity('title')]
class Process
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, unique: true)]
    private ?string $title = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(enumType: ProcessStatus::class)]
    private ?ProcessStatus $status = null;

    #[ORM\Column]
    private ?\DateTime $createdAt = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTime $updatedAt = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): static
    {
        $this->id = $id;

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getStatus(): ?ProcessStatus
    {
        return $this->status;
    }

    /**
     * Set status assigns enum value based on given string.
     * @param ProcessStatus|string $status
     * @throws \DomainException
     * @return Process
     */
    public function setStatus($status): static
    {
        if (is_string($status)) {
            $status = ProcessStatus::from($status);
        }

        if ($status === ProcessStatus::done && $this->status !== ProcessStatus::in_progress) {
            throw new \DomainException('A process can only be marked as "done" if it was previously "in_progress".');
        }

        $this->status = $status;

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

    #[ORM\PrePersist]
    public function setCreatedAtValue(): void
    {
        $this->createdAt = new \DateTime();
    }

    public function getUpdatedAt(): ?\DateTime
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTime $updatedAt): static
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    #[ORM\PreUpdate]
    public function setUpdatedAtValue(): void
    {
        $this->updatedAt = new \DateTime();
    }

    /**
     * Return true if set of rules prevents the entity from being deleted.
     * @return bool
     */
    public function isProtected(): bool
    {
        // If current status equals status done, mark as protected.
        return $this->status === ProcessStatus::done;
    }
}
