<?php

namespace App\Entity;

use App\Repository\AbsenceRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AbsenceRepository::class)]
class Absence
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $matricule = null;

    #[ORM\Column]
    private ?int $type_absence = null;

    #[ORM\Column]
    private ?\DateTime $deb_abs = null;

    #[ORM\Column]
    private ?\DateTime $fin_abs = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTime $date = null;

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

    public function getTypeAbsence(): ?int
    {
        return $this->type_absence;
    }

    public function setTypeAbsence(int $type_absence): static
    {
        $this->type_absence = $type_absence;

        return $this;
    }

    public function getDebAbs(): ?\DateTime
    {
        return $this->deb_abs;
    }

    public function setDebAbs(\DateTime $deb_abs): static
    {
        $this->deb_abs = $deb_abs;

        return $this;
    }

    public function getFinAbs(): ?\DateTime
    {
        return $this->fin_abs;
    }

    public function setFinAbs(\DateTime $fin_abs): static
    {
        $this->fin_abs = $fin_abs;

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
