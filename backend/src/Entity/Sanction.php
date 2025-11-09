<?php

namespace App\Entity;

use App\Repository\SanctionRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SanctionRepository::class)]
class Sanction
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $matricule = null;

    #[ORM\Column]
    private ?int $type_santion = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTime $date_insert = null;

    #[ORM\Column]
    private ?int $matricule_insert = null;

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

    public function getTypeSantion(): ?int
    {
        return $this->type_santion;
    }

    public function setTypeSantion(int $type_santion): static
    {
        $this->type_santion = $type_santion;

        return $this;
    }

    public function getDateInsert(): ?\DateTime
    {
        return $this->date_insert;
    }

    public function setDateInsert(\DateTime $date_insert): static
    {
        $this->date_insert = $date_insert;

        return $this;
    }

    public function getMatriculeInsert(): ?int
    {
        return $this->matricule_insert;
    }

    public function setMatriculeInsert(int $matricule_insert): static
    {
        $this->matricule_insert = $matricule_insert;

        return $this;
    }
}
