<?php

namespace App\Controller\Api;

use App\Entity\Enum\RecompenseType;
use App\Entity\Recompense;
use App\Repository\ProgrammeFideliteRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/admin/fidelite')]
class AdminFideliteController extends AbstractApiController
{
    #[Route('/programme', name: 'api_admin_fidelite_programme_show', methods: ['GET'])]
    public function showProgramme(ProgrammeFideliteRepository $repository): JsonResponse
    {
        $programme = $repository->getSingleton();

        return $this->json([
            'pointsParResa' => $programme->getPointsParResa(),
            'seuilRecompense' => $programme->getSeuilRecompense(),
            'actif' => $programme->isActif(),
        ]);
    }

    #[Route('/programme', name: 'api_admin_fidelite_programme_update', methods: ['PUT'])]
    public function updateProgramme(Request $request, ProgrammeFideliteRepository $repository, EntityManagerInterface $em): JsonResponse
    {
        $programme = $repository->getSingleton();
        $data = json_decode($request->getContent(), true) ?? [];

        if (isset($data['pointsParResa'])) {
            $programme->setPointsParResa((int) $data['pointsParResa']);
        }
        if (isset($data['seuilRecompense'])) {
            $programme->setSeuilRecompense((int) $data['seuilRecompense']);
        }
        if (isset($data['actif'])) {
            $programme->setActif((bool) $data['actif']);
        }

        $em->flush();

        return $this->json([
            'pointsParResa' => $programme->getPointsParResa(),
            'seuilRecompense' => $programme->getSeuilRecompense(),
            'actif' => $programme->isActif(),
        ]);
    }

    #[Route('/recompenses', name: 'api_admin_recompenses_create', methods: ['POST'])]
    public function createRecompense(Request $request, ProgrammeFideliteRepository $programmeRepository, EntityManagerInterface $em): JsonResponse
    {
        $data = json_decode($request->getContent(), true) ?? [];
        $type = RecompenseType::tryFrom($data['type'] ?? '');

        if (null === $type) {
            return $this->json(['message' => 'Type de récompense invalide.'], Response::HTTP_BAD_REQUEST);
        }

        $recompense = (new Recompense())
            ->setLibelle($data['libelle'] ?? '')
            ->setType($type)
            ->setSeuilPoints((int) ($data['seuilPoints'] ?? 0))
            ->setProgramme($programmeRepository->getSingleton());

        $em->persist($recompense);
        $em->flush();

        return $this->json($this->recompensePayload($recompense), Response::HTTP_CREATED);
    }

    #[Route('/recompenses/{id}', name: 'api_admin_recompenses_update', methods: ['PUT'])]
    public function updateRecompense(Recompense $recompense, Request $request, EntityManagerInterface $em): JsonResponse
    {
        $data = json_decode($request->getContent(), true) ?? [];

        if (isset($data['libelle'])) {
            $recompense->setLibelle($data['libelle']);
        }
        if (isset($data['type'])) {
            $type = RecompenseType::tryFrom($data['type']);
            if (null === $type) {
                return $this->json(['message' => 'Type de récompense invalide.'], Response::HTTP_BAD_REQUEST);
            }
            $recompense->setType($type);
        }
        if (isset($data['seuilPoints'])) {
            $recompense->setSeuilPoints((int) $data['seuilPoints']);
        }

        $em->flush();

        return $this->json($this->recompensePayload($recompense));
    }

    #[Route('/recompenses/{id}', name: 'api_admin_recompenses_delete', methods: ['DELETE'])]
    public function deleteRecompense(Recompense $recompense, EntityManagerInterface $em): JsonResponse
    {
        $em->remove($recompense);
        $em->flush();

        return $this->json(['message' => 'Récompense supprimée.']);
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
