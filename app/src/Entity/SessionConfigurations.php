<?php

namespace App\Entity;

use App\Repository\SessionConfigurationsRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SessionConfigurationsRepository::class)]
class SessionConfigurations
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $day_number = null;

    #[ORM\Column(type: Types::TIME_MUTABLE)]
    private ?\DateTimeInterface $start_time = null;

    #[ORM\Column(length: 255)]
    private ?string $duration_minutes = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $start_date = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDayNumber(): ?int
    {
        return $this->day_number;
    }

    public function setDayNumber(int $day_number): static
    {
        $this->day_number = $day_number;

        return $this;
    }

    public function getStartTime(): ?\DateTimeInterface
    {
        return $this->start_time;
    }

    public function setStartTime(\DateTimeInterface $start_time): static
    {
        $this->start_time = $start_time;

        return $this;
    }

    public function getDurationMinutes(): ?string
    {
        return $this->duration_minutes;
    }

    public function setDurationMinutes(string $duration_minutes): static
    {
        $this->duration_minutes = $duration_minutes;

        return $this;
    }

    public function getStartDate(): ?\DateTimeInterface
    {
        return $this->start_date;
    }

    public function setStartDate(?\DateTimeInterface $start_date): static
    {
        $this->start_date = $start_date;

        return $this;
    }
}
