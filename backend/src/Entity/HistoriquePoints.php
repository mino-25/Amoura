<?php

namespace App\Entity;

use App\Entity\Enum\OperationType;
use App\Repository\HistoriquePointsRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: HistoriquePointsRepository::class)]
#[ORM\Table(name: 'historique_points')]
class HistoriquePoints
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private int $pointsGagnes;

    #[ORM\Column(type: Types::STRING, enumType: OperationType::class)]
    private OperationType $typeOperation;

    #[ORM\Column]
    private \DateTimeImmutable $dateOperation;

    #[ORM\Column]
    private int $soldeApres;

    #[ORM\ManyToOne(targetEntity: Utilisateur::class, inversedBy: 'historiquePoints')]
    #[ORM\JoinColumn(name: 'utilisateur_id', nullable: false, onDelete: 'CASCADE')]
    private Utilisateur $utilisateur;

    #[ORM\ManyToOne(targetEntity: Reservation::class)]
    #[ORM\JoinColumn(name: 'reservation_id', nullable: true, onDelete: 'SET NULL')]
    private ?Reservation $reservation = null;

    public function __construct()
    {
        $this->dateOperation = new \DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPointsGagnes(): int
    {
        return $this->pointsGagnes;
    }

    public function setPointsGagnes(int $pointsGagnes): static
    {
        $this->pointsGagnes = $pointsGagnes;

        return $this;
    }

    public function getTypeOperation(): OperationType
    {
        return $this->typeOperation;
    }

    public function setTypeOperation(OperationType $typeOperation): static
    {
        $this->typeOperation = $typeOperation;

        return $this;
    }

    public function getDateOperation(): \DateTimeImmutable
    {
        return $this->dateOperation;
    }

    public function getSoldeApres(): int
    {
        return $this->soldeApres;
    }

    public function setSoldeApres(int $soldeApres): static
    {
        $this->soldeApres = $soldeApres;

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

    public function getReservation(): ?Reservation
    {
        return $this->reservation;
    }

    public function setReservation(?Reservation $reservation): static
    {
        $this->reservation = $reservation;

        return $this;
    }
}
