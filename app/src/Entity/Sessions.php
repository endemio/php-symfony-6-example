<?php

namespace App\Entity;

use App\Repository\SessionsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
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

    #[ORM\OneToMany(mappedBy: 'session', targetEntity: SessionMembers::class)]
    private Collection $sessionMembers;

    public function __construct()
    {
        $this->sessionMembers = new ArrayCollection();
    }

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

    /**
     * @return Collection<int, SessionMembers>
     */
    public function getSessionMembers(): Collection
    {
        return $this->sessionMembers;
    }

    public function addSessionMember(SessionMembers $sessionMember): static
    {
        if (!$this->sessionMembers->contains($sessionMember)) {
            $this->sessionMembers->add($sessionMember);
            $sessionMember->setSession($this);
        }

        return $this;
    }

    public function removeSessionMember(SessionMembers $sessionMember): static
    {
        if ($this->sessionMembers->removeElement($sessionMember)) {
            // set the owning side to null (unless already changed)
            if ($sessionMember->getSession() === $this) {
                $sessionMember->setSession(null);
            }
        }

        return $this;
    }
}
