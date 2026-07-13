<?php

namespace App\Service;

use App\Entity\EchangeRecompense;
use App\Entity\Enum\OperationType;
use App\Entity\HistoriquePoints;
use App\Entity\Recompense;
use App\Entity\Reservation;
use App\Entity\Utilisateur;
use App\Exception\ReservationException;
use App\Repository\HistoriquePointsRepository;
use App\Repository\ProgrammeFideliteRepository;
use Doctrine\ORM\EntityManagerInterface;

class FideliteService
{
    public function __construct(
        private readonly EntityManagerInterface $em,
        private readonly ProgrammeFideliteRepository $programmeRepository,
        private readonly HistoriquePointsRepository $historiqueRepository,
    ) {
    }

    public function attributePoints(Reservation $reservation): HistoriquePoints
    {
        $programme = $this->programmeRepository->getSingleton();
        $utilisateur = $reservation->getUtilisateur();
        $points = $programme->isActif() ? $programme->getPointsParResa() : 0;

        $nouveauSolde = $this->historiqueRepository->getSoldeActuel($utilisateur) + $points;

        $historique = (new HistoriquePoints())
            ->setPointsGagnes($points)
            ->setTypeOperation(OperationType::GAIN_RESERVATION)
            ->setSoldeApres($nouveauSolde)
            ->setUtilisateur($utilisateur)
            ->setReservation($reservation);

        $this->em->persist($historique);
        $this->em->flush();

        return $historique;
    }

    public function exchange(Utilisateur $utilisateur, Recompense $recompense): EchangeRecompense
    {
        $soldeActuel = $this->historiqueRepository->getSoldeActuel($utilisateur);

        if ($soldeActuel < $recompense->getSeuilPoints()) {
            throw new ReservationException('Solde de points insuffisant pour cette récompense.');
        }

        $nouveauSolde = $soldeActuel - $recompense->getSeuilPoints();

        $historique = (new HistoriquePoints())
            ->setPointsGagnes(-$recompense->getSeuilPoints())
            ->setTypeOperation(OperationType::ECHANGE_RECOMPENSE)
            ->setSoldeApres($nouveauSolde)
            ->setUtilisateur($utilisateur);

        $echange = (new EchangeRecompense())
            ->setUtilisateur($utilisateur)
            ->setRecompense($recompense);

        $this->em->persist($historique);
        $this->em->persist($echange);
        $this->em->flush();

        return $echange;
    }

    public function getBalance(Utilisateur $utilisateur): int
    {
        return $this->historiqueRepository->getSoldeActuel($utilisateur);
    }
}
