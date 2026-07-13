<?php

namespace App\Repository;

use App\Entity\Creneau;
use App\Entity\Enum\ReservationStatut;
use App\Entity\TableResto;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<TableResto>
 */
class TableRestoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TableResto::class);
    }

    /**
     * Trouve la table disponible la plus adaptée (capacité au plus proche du besoin)
     * pour une date et un créneau donnés, en excluant les tables déjà réservées.
     */
    public function findAvailableTable(\DateTimeImmutable $date, Creneau $creneau, int $nbCouverts): ?TableResto
    {
        $qb = $this->createQueryBuilder('t')
            ->andWhere('t.disponible = true')
            ->andWhere('t.capacite >= :nbCouverts')
            ->andWhere(
                't.id NOT IN (
                    SELECT IDENTITY(r2.table) FROM App\Entity\Reservation r2
                    WHERE r2.dateReservation = :date
                    AND r2.creneau = :creneau
                    AND r2.statut != :annulee
                )'
            )
            ->setParameter('nbCouverts', $nbCouverts)
            ->setParameter('date', $date)
            ->setParameter('creneau', $creneau)
            ->setParameter('annulee', ReservationStatut::ANNULEE)
            ->orderBy('t.capacite', 'ASC')
            ->setMaxResults(1);

        return $qb->getQuery()->getOneOrNullResult();
    }
}
