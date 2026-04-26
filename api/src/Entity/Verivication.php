<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\VerivicationRepository;

#[ORM\Entity(repositoryClass: VerivicationRepository::class)]
class Verivication
{
    #[ORM\Id]
    #[ORM\Column]
    #[ORM\GeneratedValue]
    private ?int $id = null;

    #[ORM\JoinColumn(nullable: false)]
    #[ORM\ManyToOne(inversedBy: 'verivications')]
    private ?User $user = null;

    #[ORM\Column(nullable: true)]
    private ?bool $phone = null;

    #[ORM\Column(nullable: true)]
    private ?bool $identity = null;

    #[ORM\JoinColumn(nullable: false)]
    #[ORM\ManyToOne(inversedBy: 'verivications')]
    private ?VerivicationStatus $status = null;

    #[ORM\Column(nullable: true)]
    private ?bool $active = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    public function isPhone(): ?bool
    {
        return $this->phone;
    }

    public function setPhone(?bool $phone): static
    {
        $this->phone = $phone;

        return $this;
    }

    public function isIdentity(): ?bool
    {
        return $this->identity;
    }

    public function setIdentity(?bool $identity): static
    {
        $this->identity = $identity;

        return $this;
    }

    public function getStatus(): ?VerivicationStatus
    {
        return $this->status;
    }

    public function setStatus(?VerivicationStatus $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function isActive(): ?bool
    {
        return $this->active;
    }

    public function setActive(?bool $active): static
    {
        $this->active = $active;

        return $this;
    }
}
