<?php

namespace App\Controller\Api;

use App\DTO\ReservationRequest;
use App\Entity\Reservation;
use App\Exception\ReservationException;
use App\Repository\ReservationRepository;
use App\Service\ReservationService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/api/reservations')]
class ReservationController extends AbstractApiController
{
    public function __construct(private readonly ReservationService $reservationService)
    {
    }

    #[Route('/disponibilites', name: 'api_reservations_disponibilites', methods: ['GET'])]
    public function disponibilites(Request $request): JsonResponse
    {
        $dateParam = $request->query->get('date');
        $nbCouverts = $request->query->getInt('nbCouverts', 1);

        if (null === $dateParam) {
            return $this->json(['message' => 'Le paramètre date est requis.'], Response::HTTP_BAD_REQUEST);
        }

        try {
            $date = new \DateTimeImmutable($dateParam);
        } catch (\Exception) {
            return $this->json(['message' => 'Date invalide.'], Response::HTTP_BAD_REQUEST);
        }

        return $this->json($this->reservationService->getDisponibilites($date, $nbCouverts));
    }

    #[Route('', name: 'api_reservations_list', methods: ['GET'])]
    public function list(ReservationRepository $repository): JsonResponse
    {
        $reservations = $repository->findUpcomingForUser($this->currentUser());

        return $this->json(array_map($this->reservationPayload(...), $reservations));
    }

    #[Route('', name: 'api_reservations_create', methods: ['POST'])]
    public function create(Request $request, SerializerInterface $serializer, ValidatorInterface $validator): JsonResponse
    {
        /** @var ReservationRequest $dto */
        $dto = $serializer->deserialize($request->getContent(), ReservationRequest::class, 'json');

        $violations = $validator->validate($dto);
        if (\count($violations) > 0) {
            return $this->validationErrorResponse($violations);
        }

        try {
            $reservation = $this->reservationService->create($dto, $this->currentUser());
        } catch (ReservationException $e) {
            return $this->json(['message' => $e->getMessage()], Response::HTTP_CONFLICT);
        }

        return $this->json($this->reservationPayload($reservation), Response::HTTP_CREATED);
    }

    #[Route('/{id}', name: 'api_reservations_cancel', methods: ['DELETE'])]
    public function cancel(Reservation $reservation): JsonResponse
    {
        try {
            $this->reservationService->cancel($reservation, $this->currentUser());
        } catch (ReservationException $e) {
            return $this->json(['message' => $e->getMessage()], Response::HTTP_CONFLICT);
        }

        return $this->json(['message' => 'Réservation annulée.']);
    }

    private function reservationPayload(Reservation $reservation): array
    {
        return [
            'id' => $reservation->getId(),
            'date' => $reservation->getDateReservation()->format('Y-m-d'),
            'heure' => $reservation->getCreneau()->getHeureDebut()->format('H:i'),
            'nbCouverts' => $reservation->getNbCouverts(),
            'statut' => $reservation->getStatut()->value,
            'honoree' => $reservation->isHonoree(),
        ];
    }
}
