<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use App\Repository\VerivicationStatusRepository;
use Doctrine\Common\Collections\ArrayCollection;

#[ORM\Entity(repositoryClass: VerivicationStatusRepository::class)]
class VerivicationStatus
{
    #[ORM\Id]
    #[ORM\Column]
    #[ORM\GeneratedValue]
    private ?int $id = null;

    #[ORM\Column(length: 255, unique: true)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $color = null;

    #[ORM\Column(nullable: true)]
    private ?bool $active = null;

    /**
     * @var Collection<int, Verivication>
     */
    #[ORM\OneToMany(targetEntity: Verivication::class, mappedBy: 'status')]
    private Collection $verivications;

    public function __construct()
    {
        $this->verivications = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getColor(): ?string
    {
        return $this->color;
    }

    public function setColor(string $color): static
    {
        $this->color = $color;

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

    /**
     * @return Collection<int, Verivication>
     */
    public function getVerivications(): Collection
    {
        return $this->verivications;
    }

    public function addVerivication(Verivication $verivication): static
    {
        if (!$this->verivications->contains($verivication)) {
            $this->verivications->add($verivication);
            $verivication->setStatus($this);
        }

        return $this;
    }

    public function removeVerivication(Verivication $verivication): static
    {
        if ($this->verivications->removeElement($verivication)) {
            // set the owning side to null (unless already changed)
            if ($verivication->getStatus() === $this) {
                $verivication->setStatus(null);
            }
        }

        return $this;
    }
}
