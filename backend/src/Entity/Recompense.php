<?php

namespace App\Entity;

use App\Entity\Enum\RecompenseType;
use App\Repository\RecompenseRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RecompenseRepository::class)]
#[ORM\Table(name: 'recompense')]
class Recompense
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    private string $libelle;

    #[ORM\Column(type: Types::STRING, enumType: RecompenseType::class)]
    private RecompenseType $type;

    #[ORM\Column]
    private int $seuilPoints;

    #[ORM\ManyToOne(targetEntity: ProgrammeFidelite::class, inversedBy: 'recompenses')]
    #[ORM\JoinColumn(name: 'programme_id', nullable: false)]
    private ProgrammeFidelite $programme;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLibelle(): string
    {
        return $this->libelle;
    }

    public function setLibelle(string $libelle): static
    {
        $this->libelle = $libelle;

        return $this;
    }

    public function getType(): RecompenseType
    {
        return $this->type;
    }

    public function setType(RecompenseType $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function getSeuilPoints(): int
    {
        return $this->seuilPoints;
    }

    public function setSeuilPoints(int $seuilPoints): static
    {
        $this->seuilPoints = $seuilPoints;

        return $this;
    }

    public function getProgramme(): ProgrammeFidelite
    {
        return $this->programme;
    }

    public function setProgramme(ProgrammeFidelite $programme): static
    {
        $this->programme = $programme;

        return $this;
    }
}
