<?php

namespace App\Service;

use App\DTO\ReservationRequest;
use App\Entity\Creneau;
use App\Entity\Enum\ReservationStatut;
use App\Entity\Reservation;
use App\Entity\Utilisateur;
use App\Exception\ReservationException;
use App\Repository\CreneauRepository;
use App\Repository\TableRestoRepository;
use Doctrine\ORM\EntityManagerInterface;

class ReservationService
{
    public function __construct(
        private readonly EntityManagerInterface $em,
        private readonly TableRestoRepository $tableRepository,
        private readonly CreneauRepository $creneauRepository,
        private readonly MailerService $mailer,
    ) {
    }

    public function create(ReservationRequest $dto, Utilisateur $utilisateur): Reservation
    {
        $date = new \DateTimeImmutable($dto->date);
        $today = new \DateTimeImmutable('today');

        if ($date < $today) {
            throw new ReservationException('Impossible de réserver une date passée.');
        }

        $creneau = $this->creneauRepository->find($dto->creneauId);
        if (!$creneau instanceof Creneau || !$creneau->isActif()) {
            throw new ReservationException('Créneau invalide ou fermé.');
        }

        $table = $this->tableRepository->findAvailableTable($date, $creneau, $dto->nbCouverts);
        if (null === $table) {
            throw new ReservationException('Aucune table disponible pour ce créneau.');
        }

        $reservation = (new Reservation())
            ->setDateReservation($date)
            ->setNbCouverts($dto->nbCouverts)
            ->setStatut(ReservationStatut::CONFIRMEE)
            ->setUtilisateur($utilisateur)
            ->setTable($table)
            ->setCreneau($creneau);

        $this->em->persist($reservation);
        $this->em->flush();

        $this->mailer->sendConfirmation($reservation);

        return $reservation;
    }

    public function cancel(Reservation $reservation, Utilisateur $utilisateur): void
    {
        if ($reservation->getUtilisateur()->getId() !== $utilisateur->getId()) {
            throw new ReservationException('Vous ne pouvez annuler que vos propres réservations.');
        }

        if (ReservationStatut::ANNULEE === $reservation->getStatut()) {
            throw new ReservationException('Cette réservation est déjà annulée.');
        }

        $reservation->setStatut(ReservationStatut::ANNULEE);
        $this->em->flush();

        $this->mailer->sendCancellation($reservation);
    }

    /**
     * @return array{creneauId: int, heureDebut: string, placesRestantes: int}[]
     */
    public function getDisponibilites(\DateTimeImmutable $date, int $nbCouverts): array
    {
        $resultats = [];
        foreach ($this->creneauRepository->findActifs() as $creneau) {
            $table = $this->tableRepository->findAvailableTable($date, $creneau, $nbCouverts);
            if (null !== $table) {
                $resultats[] = [
                    'creneauId' => $creneau->getId(),
                    'heureDebut' => $creneau->getHeureDebut()->format('H:i'),
                    'placesRestantes' => $table->getCapacite(),
                ];
            }
        }

        return $resultats;
    }
}
