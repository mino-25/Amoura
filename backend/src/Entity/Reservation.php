<?php

namespace App\Entity;

use App\Entity\Enum\ReservationStatut;
use App\Repository\ReservationRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ReservationRepository::class)]
#[ORM\Table(name: 'reservation')]
#[ORM\Index(columns: ['date_reservation'], name: 'idx_date')]
class Reservation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATE_IMMUTABLE)]
    private \DateTimeImmutable $dateReservation;

    #[ORM\Column(type: 'smallint')]
    private int $nbCouverts;

    #[ORM\Column(type: Types::STRING, enumType: ReservationStatut::class)]
    private ReservationStatut $statut = ReservationStatut::EN_ATTENTE;

    #[ORM\Column]
    private bool $honoree = false;

    #[ORM\Column]
    private \DateTimeImmutable $createdAt;

    #[ORM\ManyToOne(targetEntity: Utilisateur::class, inversedBy: 'reservations')]
    #[ORM\JoinColumn(name: 'utilisateur_id', nullable: false, onDelete: 'CASCADE')]
    private Utilisateur $utilisateur;

    #[ORM\ManyToOne(targetEntity: TableResto::class, inversedBy: 'reservations')]
    #[ORM\JoinColumn(name: 'table_id', nullable: false)]
    private TableResto $table;

    #[ORM\ManyToOne(targetEntity: Creneau::class, inversedBy: 'reservations')]
    #[ORM\JoinColumn(name: 'creneau_id', nullable: false)]
    private Creneau $creneau;

    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateReservation(): \DateTimeImmutable
    {
        return $this->dateReservation;
    }

    public function setDateReservation(\DateTimeImmutable $dateReservation): static
    {
        $this->dateReservation = $dateReservation;

        return $this;
    }

    public function getNbCouverts(): int
    {
        return $this->nbCouverts;
    }

    public function setNbCouverts(int $nbCouverts): static
    {
        $this->nbCouverts = $nbCouverts;

        return $this;
    }

    public function getStatut(): ReservationStatut
    {
        return $this->statut;
    }

    public function setStatut(ReservationStatut $statut): static
    {
        $this->statut = $statut;

        return $this;
    }

    public function isHonoree(): bool
    {
        return $this->honoree;
    }

    public function setHonoree(bool $honoree): static
    {
        $this->honoree = $honoree;

        return $this;
    }

    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
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

    public function getTable(): TableResto
    {
        return $this->table;
    }

    public function setTable(TableResto $table): static
    {
        $this->table = $table;

        return $this;
    }

    public function getCreneau(): Creneau
    {
        return $this->creneau;
    }

    public function setCreneau(Creneau $creneau): static
    {
        $this->creneau = $creneau;

        return $this;
    }
}
