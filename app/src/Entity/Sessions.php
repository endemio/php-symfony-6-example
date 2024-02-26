<?php

namespace App\Entity;

use App\Repository\SessionsRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SessionsRepository::class)]
class Sessions
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $start_time = null;

    #[ORM\Column(type: Types::BIGINT)]
    private ?string $session_configuration_id = null;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getSessionConfigurationId(): ?string
    {
        return $this->session_configuration_id;
    }

    public function setSessionConfigurationId(string $session_configuration_id): static
    {
        $this->session_configuration_id = $session_configuration_id;

        return $this;
    }

}
