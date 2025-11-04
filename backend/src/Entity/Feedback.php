<?php

namespace App\Entity;

use App\Repository\FeedbackRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FeedbackRepository::class)]
class Feedback
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $matricule_concerned = null;

    #[ORM\Column]
    private ?int $matricule_insert = null;

    #[ORM\Column]
    private ?\DateTime $date_inserted = null;

    #[ORM\Column]
    private ?int $critere_1 = null;

    #[ORM\Column]
    private ?int $critere_2 = null;

    #[ORM\Column]
    private ?int $critere_3 = null;

    #[ORM\Column]
    private ?int $critere_4 = null;

    #[ORM\Column]
    private ?int $critere_5 = null;

    #[ORM\Column(length: 255)]
    private ?string $commentary = null;

    #[ORM\Column]
    private ?int $type_feedback = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): static
    {
        $this->id = $id;

        return $this;
    }

    public function getMatriculeConcerned(): ?int
    {
        return $this->matricule_concerned;
    }

    public function setMatriculeConcerned(int $matricule_concerned): static
    {
        $this->matricule_concerned = $matricule_concerned;

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

    public function getDateInserted(): ?\DateTime
    {
        return $this->date_inserted;
    }

    public function setDateInserted(\DateTime $date_inserted): static
    {
        $this->date_inserted = $date_inserted;

        return $this;
    }

    public function getCritere1(): ?int
    {
        return $this->critere_1;
    }

    public function setCritere1(int $critere_1): static
    {
        $this->critere_1 = $critere_1;

        return $this;
    }

    public function getCritere2(): ?int
    {
        return $this->critere_2;
    }

    public function setCritere2(int $critere_2): static
    {
        $this->critere_2 = $critere_2;

        return $this;
    }

    public function getCritere3(): ?int
    {
        return $this->critere_3;
    }

    public function setCritere3(int $critere_3): static
    {
        $this->critere_3 = $critere_3;

        return $this;
    }

    public function getCritere4(): ?int
    {
        return $this->critere_4;
    }

    public function setCritere4(int $critere_4): static
    {
        $this->critere_4 = $critere_4;

        return $this;
    }

    public function getCritere5(): ?int
    {
        return $this->critere_5;
    }

    public function setCritere5(int $critere_5): static
    {
        $this->critere_5 = $critere_5;

        return $this;
    }

    public function getCommentary(): ?string
    {
        return $this->commentary;
    }

    public function setCommentary(string $commentary): static
    {
        $this->commentary = $commentary;

        return $this;
    }

    public function getTypeFeedback(): ?int
    {
        return $this->type_feedback;
    }

    public function setTypeFeedback(int $type_feedback): static
    {
        $this->type_feedback = $type_feedback;

        return $this;
    }
}
