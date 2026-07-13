<?php

namespace App\Controller\Api;

use App\Entity\Enum\ReservationStatut;
use App\Repository\HistoriquePointsRepository;
use App\Repository\ReservationRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/admin/dashboard')]
class AdminDashboardController extends AbstractApiController
{
    #[Route('', name: 'api_admin_dashboard', methods: ['GET'])]
    public function index(ReservationRepository $reservationRepository, HistoriquePointsRepository $historiqueRepository): JsonResponse
    {
        $today = new \DateTimeImmutable('today');
        $yesterday = $today->modify('-1 day');
        $startOfMonth = $today->modify('first day of this month');

        $topFideles = array_map(
            static fn (array $row) => [
                'nom' => $row['utilisateur']->getPrenom().' '.$row['utilisateur']->getNom(),
                'points' => $row['solde'],
            ],
            $historiqueRepository->findTopFideles(5)
        );

        return $this->json([
            'date' => $today->format('Y-m-d'),
            'reservationsCeSoir' => $reservationRepository->countByDate($today, ReservationStatut::ANNULEE),
            'reservationsHier' => $reservationRepository->countByDate($yesterday, ReservationStatut::ANNULEE),
            'clientsCeMois' => $reservationRepository->countDistinctClientsSince($startOfMonth),
            'clientsFideles' => $historiqueRepository->countDistinctClients(),
            'annulationsAujourdhui' => $reservationRepository->countByDateAndStatut($today, ReservationStatut::ANNULEE),
            'annulationsHier' => $reservationRepository->countByDateAndStatut($yesterday, ReservationStatut::ANNULEE),
            'topFideles' => $topFideles,
        ]);
    }
}
