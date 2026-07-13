<?php

namespace App\Entity;

use App\Entity\Enum\UserRole;
use App\Repository\UtilisateurRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UtilisateurRepository::class)]
#[ORM\Table(name: 'utilisateur')]
class Utilisateur implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private string $nom;

    #[ORM\Column(length: 50)]
    private string $prenom;

    #[ORM\Column(length: 180, unique: true)]
    private string $email;

    #[ORM\Column(length: 255)]
    private string $motDePasse;

    #[ORM\Column(type: Types::STRING, enumType: UserRole::class)]
    private UserRole $role = UserRole::CLIENT;

    #[ORM\Column]
    private \DateTimeImmutable $createdAt;

    /** @var Collection<int, Reservation> */
    #[ORM\OneToMany(targetEntity: Reservation::class, mappedBy: 'utilisateur', orphanRemoval: true)]
    private Collection $reservations;

    /** @var Collection<int, HistoriquePoints> */
    #[ORM\OneToMany(targetEntity: HistoriquePoints::class, mappedBy: 'utilisateur', orphanRemoval: true)]
    private Collection $historiquePoints;

    /** @var Collection<int, EchangeRecompense> */
    #[ORM\OneToMany(targetEntity: EchangeRecompense::class, mappedBy: 'utilisateur', orphanRemoval: true)]
    private Collection $echangesRecompense;

    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable();
        $this->reservations = new ArrayCollection();
        $this->historiquePoints = new ArrayCollection();
        $this->echangesRecompense = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrenom(): string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): static
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getUserIdentifier(): string
    {
        return $this->email;
    }

    public function getPassword(): string
    {
        return $this->motDePasse;
    }

    public function setPassword(string $motDePasse): static
    {
        $this->motDePasse = $motDePasse;

        return $this;
    }

    public function getRoleEnum(): UserRole
    {
        return $this->role;
    }

    public function setRoleEnum(UserRole $role): static
    {
        $this->role = $role;

        return $this;
    }

    public function getRoles(): array
    {
        $roles = ['ROLE_USER'];
        if (UserRole::ADMIN === $this->role) {
            $roles[] = 'ROLE_ADMIN';
        }

        return array_unique($roles);
    }

    public function eraseCredentials(): void
    {
        // Aucune donnée sensible temporaire à effacer
    }

    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }

    /** @return Collection<int, Reservation> */
    public function getReservations(): Collection
    {
        return $this->reservations;
    }

    /** @return Collection<int, HistoriquePoints> */
    public function getHistoriquePoints(): Collection
    {
        return $this->historiquePoints;
    }

    /** @return Collection<int, EchangeRecompense> */
    public function getEchangesRecompense(): Collection
    {
        return $this->echangesRecompense;
    }
}
