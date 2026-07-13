<?php

namespace App\Repository;

use App\Entity\ProgrammeFidelite;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ProgrammeFidelite>
 */
class ProgrammeFideliteRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ProgrammeFidelite::class);
    }

    /**
     * Le programme de fidélité est un singleton : une seule ligne en base.
     */
    public function getSingleton(): ProgrammeFidelite
    {
        $programme = $this->createQueryBuilder('p')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();

        if (!$programme instanceof ProgrammeFidelite) {
            $programme = new ProgrammeFidelite();
            $this->getEntityManager()->persist($programme);
            $this->getEntityManager()->flush();
        }

        return $programme;
    }
}
