<?php

namespace App\Repository;

use App\Entity\HistoriquePoints;
use App\Entity\Utilisateur;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<HistoriquePoints>
 */
class HistoriquePointsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, HistoriquePoints::class);
    }

    public function getSoldeActuel(Utilisateur $utilisateur): int
    {
        $dernier = $this->createQueryBuilder('h')
            ->andWhere('h.utilisateur = :user')
            ->setParameter('user', $utilisateur)
            ->orderBy('h.dateOperation', 'DESC')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();

        return $dernier?->getSoldeApres() ?? 0;
    }

    /** @return HistoriquePoints[] */
    public function findForUser(Utilisateur $utilisateur): array
    {
        return $this->createQueryBuilder('h')
            ->andWhere('h.utilisateur = :user')
            ->setParameter('user', $utilisateur)
            ->orderBy('h.dateOperation', 'DESC')
            ->getQuery()
            ->getResult();
    }

    public function countDistinctClients(): int
    {
        return (int) $this->createQueryBuilder('h')
            ->select('COUNT(DISTINCT h.utilisateur)')
            ->getQuery()
            ->getSingleScalarResult();
    }

    /**
     * Classement des clients par solde de points actuel (le plus récent enregistrement de chacun).
     *
     * @return array{utilisateur: Utilisateur, solde: int}[]
     */
    public function findTopFideles(int $limit = 5): array
    {
        $rows = $this->getEntityManager()->createQuery(
            'SELECT h1.soldeApres AS solde, IDENTITY(h1.utilisateur) AS utilisateurId
             FROM App\Entity\HistoriquePoints h1
             WHERE h1.dateOperation = (
                 SELECT MAX(h2.dateOperation) FROM App\Entity\HistoriquePoints h2
                 WHERE h2.utilisateur = h1.utilisateur
             )
             ORDER BY h1.soldeApres DESC'
        )->setMaxResults($limit)->getArrayResult();

        $utilisateurRepository = $this->getEntityManager()->getRepository(Utilisateur::class);

        return array_map(
            static fn (array $row) => [
                'utilisateur' => $utilisateurRepository->find($row['utilisateurId']),
                'solde' => (int) $row['solde'],
            ],
            $rows
        );
    }
}
