<?php

namespace App\Entity;

use App\Repository\ReportRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ReportRepository::class)]
class Report
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $id_report = null;

    #[ORM\Column]
    private ?int $id_creator = null;

    #[ORM\Column]
    private ?int $type_report = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $content = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTime $date_generation = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTime $date_start = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTime $date_end = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdReport(): ?int
    {
        return $this->id_report;
    }

    public function setIdReport(int $id_report): static
    {
        $this->id_report = $id_report;

        return $this;
    }

    public function getIdCreator(): ?int
    {
        return $this->id_creator;
    }

    public function setIdCreator(int $id_creator): static
    {
        $this->id_creator = $id_creator;

        return $this;
    }

    public function getTypeReport(): ?int
    {
        return $this->type_report;
    }

    public function setTypeReport(int $type_report): static
    {
        $this->type_report = $type_report;

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

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): static
    {
        $this->content = $content;

        return $this;
    }

    public function getDateGeneration(): ?\DateTime
    {
        return $this->date_generation;
    }

    public function setDateGeneration(\DateTime $date_generation): static
    {
        $this->date_generation = $date_generation;

        return $this;
    }

    public function getDateStart(): ?\DateTime
    {
        return $this->date_start;
    }

    public function setDateStart(\DateTime $date_start): static
    {
        $this->date_start = $date_start;

        return $this;
    }

    public function getDateEnd(): ?\DateTime
    {
        return $this->date_end;
    }

    public function setDateEnd(\DateTime $date_end): static
    {
        $this->date_end = $date_end;

        return $this;
    }
}
