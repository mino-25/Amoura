<?php

namespace App\Service;

use App\Entity\Reservation;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class MailerService
{
    public function __construct(
        private readonly MailerInterface $mailer,
        private readonly string $mailerFrom = 'reservation@amoura-restaurant.fr',
    ) {
    }

    public function sendConfirmation(Reservation $reservation): void
    {
        $utilisateur = $reservation->getUtilisateur();

        $email = (new Email())
            ->from($this->mailerFrom)
            ->to($utilisateur->getEmail())
            ->subject('Confirmation de votre réservation chez Amoura')
            ->html($this->confirmationBody($reservation));

        $this->mailer->send($email);
    }

    public function sendCancellation(Reservation $reservation): void
    {
        $utilisateur = $reservation->getUtilisateur();

        $email = (new Email())
            ->from($this->mailerFrom)
            ->to($utilisateur->getEmail())
            ->subject('Annulation de votre réservation chez Amoura')
            ->html($this->cancellationBody($reservation));

        $this->mailer->send($email);
    }

    private function confirmationBody(Reservation $reservation): string
    {
        $date = $reservation->getDateReservation()->format('d/m/Y');
        $heure = $reservation->getCreneau()->getHeureDebut()->format('H:i');
        $couverts = $reservation->getNbCouverts();

        return <<<HTML
            <p>Bonjour {$reservation->getUtilisateur()->getPrenom()},</p>
            <p>Votre réservation chez <strong>Amoura</strong> est confirmée :</p>
            <ul>
                <li>Date : {$date}</li>
                <li>Heure : {$heure}</li>
                <li>Couverts : {$couverts}</li>
            </ul>
            <p>À très bientôt !</p>
            HTML;
    }

    private function cancellationBody(Reservation $reservation): string
    {
        $date = $reservation->getDateReservation()->format('d/m/Y');
        $heure = $reservation->getCreneau()->getHeureDebut()->format('H:i');

        return <<<HTML
            <p>Bonjour {$reservation->getUtilisateur()->getPrenom()},</p>
            <p>Votre réservation du {$date} à {$heure} chez <strong>Amoura</strong> a bien été annulée.</p>
            HTML;
    }
}
