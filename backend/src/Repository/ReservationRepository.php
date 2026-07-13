<?php

namespace App\Repository;

use App\Entity\Creneau;
use App\Entity\Enum\ReservationStatut;
use App\Entity\Reservation;
use App\Entity\Utilisateur;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Reservation>
 */
class ReservationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Reservation::class);
    }

    /**
     * Nombre de couverts déjà réservés (hors annulées) pour une date + créneau donnés.
     */
    public function countCouvertsReserves(\DateTimeImmutable $date, Creneau $creneau): int
    {
        return (int) $this->createQueryBuilder('r')
            ->select('COALESCE(SUM(r.nbCouverts), 0)')
            ->andWhere('r.dateReservation = :date')
            ->andWhere('r.creneau = :creneau')
            ->andWhere('r.statut != :annulee')
            ->setParameter('date', $date)
            ->setParameter('creneau', $creneau)
            ->setParameter('annulee', ReservationStatut::ANNULEE)
            ->getQuery()
            ->getSingleScalarResult();
    }

    /** @return Reservation[] */
    public function findUpcomingForUser(Utilisateur $utilisateur): array
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.utilisateur = :user')
            ->setParameter('user', $utilisateur)
            ->orderBy('r.dateReservation', 'DESC')
            ->getQuery()
            ->getResult();
    }

    /** @return Reservation[] */
    public function findByDate(\DateTimeImmutable $date): array
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.dateReservation = :date')
            ->setParameter('date', $date)
            ->orderBy('r.creneau', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function countByDate(\DateTimeImmutable $date, ?ReservationStatut $excluding = null): int
    {
        $qb = $this->createQueryBuilder('r')
            ->select('COUNT(r.id)')
            ->andWhere('r.dateReservation = :date')
            ->setParameter('date', $date);

        if (null !== $excluding) {
            $qb->andWhere('r.statut != :excluding')->setParameter('excluding', $excluding);
        }

        return (int) $qb->getQuery()->getSingleScalarResult();
    }

    public function countByDateAndStatut(\DateTimeImmutable $date, ReservationStatut $statut): int
    {
        return (int) $this->createQueryBuilder('r')
            ->select('COUNT(r.id)')
            ->andWhere('r.dateReservation = :date')
            ->andWhere('r.statut = :statut')
            ->setParameter('date', $date)
            ->setParameter('statut', $statut)
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function countDistinctClientsSince(\DateTimeImmutable $since): int
    {
        return (int) $this->createQueryBuilder('r')
            ->select('COUNT(DISTINCT r.utilisateur)')
            ->andWhere('r.dateReservation >= :since')
            ->setParameter('since', $since)
            ->getQuery()
            ->getSingleScalarResult();
    }
}
