<?php

namespace App\Entity;

use App\Repository\SessionMembersRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SessionMembersRepository::class)]
class SessionMembers
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Sessions::class, inversedBy: 'sessionMembers')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Sessions $session = null;

    #[ORM\ManyToOne(targetEntity: Clients::class, inversedBy: 'sessionMembers')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Clients $client = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSession(): ?Sessions
    {
        return $this->session;
    }

    public function setSession(?Sessions $session): static
    {
        $this->session = $session;

        return $this;
    }

    public function getClient(): ?Clients
    {
        return $this->client;
    }

    public function setClient(?Clients $client): static
    {
        $this->client = $client;

        return $this;
    }
}
