<?php

namespace App\Controller\Api;

use App\Entity\Creneau;
use App\Repository\CreneauRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/admin/creneaux')]
class AdminCreneauController extends AbstractApiController
{
    #[Route('', name: 'api_admin_creneaux_list', methods: ['GET'])]
    public function list(CreneauRepository $repository): JsonResponse
    {
        return $this->json(array_map($this->payload(...), $repository->findAll()));
    }

    #[Route('', name: 'api_admin_creneaux_create', methods: ['POST'])]
    public function create(Request $request, EntityManagerInterface $em): JsonResponse
    {
        $data = json_decode($request->getContent(), true) ?? [];

        $creneau = (new Creneau())
            ->setHeureDebut(new \DateTimeImmutable($data['heureDebut'] ?? '12:00'))
            ->setHeureFin(new \DateTimeImmutable($data['heureFin'] ?? '14:00'))
            ->setActif((bool) ($data['actif'] ?? true));

        $em->persist($creneau);
        $em->flush();

        return $this->json($this->payload($creneau), Response::HTTP_CREATED);
    }

    #[Route('/{id}', name: 'api_admin_creneaux_update', methods: ['PUT'])]
    public function update(Creneau $creneau, Request $request, EntityManagerInterface $em): JsonResponse
    {
        $data = json_decode($request->getContent(), true) ?? [];

        if (isset($data['heureDebut'])) {
            $creneau->setHeureDebut(new \DateTimeImmutable($data['heureDebut']));
        }
        if (isset($data['heureFin'])) {
            $creneau->setHeureFin(new \DateTimeImmutable($data['heureFin']));
        }
        if (isset($data['actif'])) {
            $creneau->setActif((bool) $data['actif']);
        }

        $em->flush();

        return $this->json($this->payload($creneau));
    }

    #[Route('/{id}', name: 'api_admin_creneaux_delete', methods: ['DELETE'])]
    public function delete(Creneau $creneau, EntityManagerInterface $em): JsonResponse
    {
        $em->remove($creneau);
        $em->flush();

        return $this->json(['message' => 'Créneau supprimé.']);
    }

    private function payload(Creneau $creneau): array
    {
        return [
            'id' => $creneau->getId(),
            'heureDebut' => $creneau->getHeureDebut()->format('H:i'),
            'heureFin' => $creneau->getHeureFin()->format('H:i'),
            'actif' => $creneau->isActif(),
        ];
    }
}
