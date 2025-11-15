<?php

namespace App\Entity;

use App\Repository\RetardRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RetardRepository::class)]
class Retard
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $matricule = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTime $date = null;

    #[ORM\Column]
    private ?float $retard_in_hour = null;

    #[ORM\ManyToOne(targetEntity: Vigie::class)]
    #[ORM\JoinColumn(nullable: true)]
    private ?Vigie $vigie = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): static
    {
        $this->id = $id;

        return $this;
    }

    public function getMatricule(): ?int
    {
        return $this->matricule;
    }

    public function setMatricule(int $matricule): static
    {
        $this->matricule = $matricule;

        return $this;
    }

    public function getDate(): ?\DateTime
    {
        return $this->date;
    }

    public function setDate(\DateTime $date): static
    {
        $this->date = $date;

        return $this;
    }

    public function getRetardInHour(): ?float
    {
        return $this->retard_in_hour;
    }

    public function setRetardInHour(float $retard_in_hour): static
    {
        $this->retard_in_hour = $retard_in_hour;

        return $this;
    }

    public function getVigie(): ?Vigie
    {
        return $this->vigie;
    }

    public function setVigie(?Vigie $vigie): self
    {
        $this->vigie = $vigie;
        return $this;
    }
}
