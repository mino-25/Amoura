<?php

namespace App\Entity;

use App\Repository\ProgrammeFideliteRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProgrammeFideliteRepository::class)]
#[ORM\Table(name: 'programme_fidelite')]
class ProgrammeFidelite
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private int $pointsParResa = 10;

    #[ORM\Column]
    private int $seuilRecompense = 100;

    #[ORM\Column]
    private bool $actif = true;

    /** @var Collection<int, Recompense> */
    #[ORM\OneToMany(targetEntity: Recompense::class, mappedBy: 'programme', orphanRemoval: true)]
    private Collection $recompenses;

    public function __construct()
    {
        $this->recompenses = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPointsParResa(): int
    {
        return $this->pointsParResa;
    }

    public function setPointsParResa(int $pointsParResa): static
    {
        $this->pointsParResa = $pointsParResa;

        return $this;
    }

    public function getSeuilRecompense(): int
    {
        return $this->seuilRecompense;
    }

    public function setSeuilRecompense(int $seuilRecompense): static
    {
        $this->seuilRecompense = $seuilRecompense;

        return $this;
    }

    public function isActif(): bool
    {
        return $this->actif;
    }

    public function setActif(bool $actif): static
    {
        $this->actif = $actif;

        return $this;
    }

    /** @return Collection<int, Recompense> */
    public function getRecompenses(): Collection
    {
        return $this->recompenses;
    }
}
