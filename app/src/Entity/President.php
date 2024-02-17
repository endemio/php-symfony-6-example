<?php

namespace App\Entity;

use App\Repository\PresidentRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PresidentRepository::class)]
class President
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $surname = null;

    #[ORM\OneToOne(mappedBy: 'president', cascade: ['persist', 'remove'])]
    private ?Country $country = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getSurname(): ?string
    {
        return $this->surname;
    }

    public function setSurname(string $surname): static
    {
        $this->surname = $surname;

        return $this;
    }

    public function getCountry(): ?Country
    {
        return $this->country;
    }

    public function setCountry(?Country $country): static
    {
        // unset the owning side of the relation if necessary
        if ($country === null && $this->country !== null) {
            $this->country->setPresident(null);
        }

        // set the owning side of the relation if necessary
        if ($country !== null && $country->getPresident() !== $this) {
            $country->setPresident($this);
        }

        $this->country = $country;

        return $this;
    }

    public function getRelationName(): string
    {
        return $this->getName() . ' ' . $this->getSurname();
    }
}
