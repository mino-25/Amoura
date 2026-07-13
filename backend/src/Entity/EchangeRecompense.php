<?php

namespace App\Entity;

use App\Entity\Enum\EchangeStatut;
use App\Repository\EchangeRecompenseRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EchangeRecompenseRepository::class)]
#[ORM\Table(name: 'echange_recompense')]
class EchangeRecompense
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private \DateTimeImmutable $dateEchange;

    #[ORM\Column(type: Types::STRING, enumType: EchangeStatut::class)]
    private EchangeStatut $statut = EchangeStatut::UTILISE;

    #[ORM\ManyToOne(targetEntity: Utilisateur::class, inversedBy: 'echangesRecompense')]
    #[ORM\JoinColumn(name: 'utilisateur_id', nullable: false, onDelete: 'CASCADE')]
    private Utilisateur $utilisateur;

    #[ORM\ManyToOne(targetEntity: Recompense::class)]
    #[ORM\JoinColumn(name: 'recompense_id', nullable: false)]
    private Recompense $recompense;

    public function __construct()
    {
        $this->dateEchange = new \DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateEchange(): \DateTimeImmutable
    {
        return $this->dateEchange;
    }

    public function getStatut(): EchangeStatut
    {
        return $this->statut;
    }

    public function setStatut(EchangeStatut $statut): static
    {
        $this->statut = $statut;

        return $this;
    }

    public function getUtilisateur(): Utilisateur
    {
        return $this->utilisateur;
    }

    public function setUtilisateur(Utilisateur $utilisateur): static
    {
        $this->utilisateur = $utilisateur;

        return $this;
    }

    public function getRecompense(): Recompense
    {
        return $this->recompense;
    }

    public function setRecompense(Recompense $recompense): static
    {
        $this->recompense = $recompense;

        return $this;
    }
}
