<?php

namespace App\Entity;

use App\Repository\ClientsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ClientsRepository::class)]
class Clients
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $first_name = null;

    #[ORM\Column(length: 255)]
    private ?string $last_name = null;

    #[ORM\OneToMany(mappedBy: 'client', targetEntity: SessionMembers::class)]
    private Collection $sessionMembers;

    public function __construct()
    {
        $this->sessionMembers = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstName(): ?string
    {
        return $this->first_name;
    }

    public function setFirstName(string $first_name): static
    {
        $this->first_name = $first_name;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->last_name;
    }

    public function setLastName(string $last_name): static
    {
        $this->last_name = $last_name;

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
            $sessionMember->setClient($this);
        }

        return $this;
    }

    public function removeSessionMember(SessionMembers $sessionMember): static
    {
        if ($this->sessionMembers->removeElement($sessionMember)) {
            // set the owning side to null (unless already changed)
            if ($sessionMember->getClient() === $this) {
                $sessionMember->setClient(null);
            }
        }

        return $this;
    }
}
