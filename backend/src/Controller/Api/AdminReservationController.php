<?php

namespace App\Controller\Api;

use App\Entity\Enum\ReservationStatut;
use App\Entity\Reservation;
use App\Repository\ReservationRepository;
use App\Service\FideliteService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/admin/reservations')]
class AdminReservationController extends AbstractApiController
{
    #[Route('', name: 'api_admin_reservations_list', methods: ['GET'])]
    public function list(Request $request, ReservationRepository $repository): JsonResponse
    {
        $dateParam = $request->query->get('date', (new \DateTimeImmutable('today'))->format('Y-m-d'));
        $date = new \DateTimeImmutable($dateParam);

        return $this->json(array_map($this->reservationPayload(...), $repository->findByDate($date)));
    }

    #[Route('/{id}/honorer', name: 'api_admin_reservations_honorer', methods: ['PATCH'])]
    public function honorer(Reservation $reservation, FideliteService $fideliteService, EntityManagerInterface $em): JsonResponse
    {
        $reservation->setHonoree(true);
        $reservation->setStatut(ReservationStatut::CONFIRMEE);
        $em->flush();

        $historique = $fideliteService->attributePoints($reservation);

        return $this->json([
            'reservation' => $this->reservationPayload($reservation),
            'pointsAttribues' => $historique->getPointsGagnes(),
            'nouveauSolde' => $historique->getSoldeApres(),
        ]);
    }

    #[Route('/{id}/statut', name: 'api_admin_reservations_statut', methods: ['PATCH'])]
    public function updateStatut(Reservation $reservation, Request $request, EntityManagerInterface $em): JsonResponse
    {
        $data = json_decode($request->getContent(), true) ?? [];
        $statut = ReservationStatut::tryFrom($data['statut'] ?? '');

        if (null === $statut) {
            return $this->json(['message' => 'Statut invalide.'], Response::HTTP_BAD_REQUEST);
        }

        $reservation->setStatut($statut);
        $em->flush();

        return $this->json($this->reservationPayload($reservation));
    }

    private function reservationPayload(Reservation $reservation): array
    {
        $utilisateur = $reservation->getUtilisateur();

        return [
            'id' => $reservation->getId(),
            'client' => $utilisateur->getPrenom().' '.$utilisateur->getNom(),
            'date' => $reservation->getDateReservation()->format('Y-m-d'),
            'heure' => $reservation->getCreneau()->getHeureDebut()->format('H:i'),
            'nbCouverts' => $reservation->getNbCouverts(),
            'statut' => $reservation->getStatut()->value,
            'honoree' => $reservation->isHonoree(),
        ];
    }
}
