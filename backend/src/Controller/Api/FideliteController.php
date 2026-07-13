<?php

namespace App\Controller\Api;

use App\Entity\HistoriquePoints;
use App\Entity\Recompense;
use App\Exception\ReservationException;
use App\Repository\HistoriquePointsRepository;
use App\Repository\RecompenseRepository;
use App\Service\FideliteService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/fidelite')]
class FideliteController extends AbstractApiController
{
    public function __construct(private readonly FideliteService $fideliteService)
    {
    }

    #[Route('', name: 'api_fidelite_show', methods: ['GET'])]
    public function show(HistoriquePointsRepository $historiqueRepository, RecompenseRepository $recompenseRepository): JsonResponse
    {
        $utilisateur = $this->currentUser();

        return $this->json([
            'solde' => $this->fideliteService->getBalance($utilisateur),
            'historique' => array_map($this->historiquePayload(...), $historiqueRepository->findForUser($utilisateur)),
            'recompenses' => array_map($this->recompensePayload(...), $recompenseRepository->findAllOrderedByPoints()),
        ]);
    }

    #[Route('/echanger/{id}', name: 'api_fidelite_exchange', methods: ['POST'])]
    public function exchange(Recompense $recompense): JsonResponse
    {
        try {
            $echange = $this->fideliteService->exchange($this->currentUser(), $recompense);
        } catch (ReservationException $e) {
            return $this->json(['message' => $e->getMessage()], Response::HTTP_CONFLICT);
        }

        return $this->json([
            'id' => $echange->getId(),
            'recompense' => $recompense->getLibelle(),
            'dateEchange' => $echange->getDateEchange()->format(\DateTimeInterface::ATOM),
        ], Response::HTTP_CREATED);
    }

    private function historiquePayload(HistoriquePoints $historique): array
    {
        return [
            'id' => $historique->getId(),
            'points' => $historique->getPointsGagnes(),
            'type' => $historique->getTypeOperation()->value,
            'date' => $historique->getDateOperation()->format(\DateTimeInterface::ATOM),
            'soldeApres' => $historique->getSoldeApres(),
        ];
    }

    private function recompensePayload(Recompense $recompense): array
    {
        return [
            'id' => $recompense->getId(),
            'libelle' => $recompense->getLibelle(),
            'type' => $recompense->getType()->value,
            'seuilPoints' => $recompense->getSeuilPoints(),
        ];
    }
}
