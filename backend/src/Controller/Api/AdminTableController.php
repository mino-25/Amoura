<?php

namespace App\Controller\Api;

use App\Entity\TableResto;
use App\Repository\TableRestoRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/admin/tables')]
class AdminTableController extends AbstractApiController
{
    #[Route('', name: 'api_admin_tables_list', methods: ['GET'])]
    public function list(TableRestoRepository $repository): JsonResponse
    {
        return $this->json(array_map($this->payload(...), $repository->findAll()));
    }

    #[Route('', name: 'api_admin_tables_create', methods: ['POST'])]
    public function create(Request $request, EntityManagerInterface $em): JsonResponse
    {
        $data = json_decode($request->getContent(), true) ?? [];

        $table = (new TableResto())
            ->setNumero((int) ($data['numero'] ?? 0))
            ->setCapacite((int) ($data['capacite'] ?? 0))
            ->setDisponible((bool) ($data['disponible'] ?? true));

        $em->persist($table);
        $em->flush();

        return $this->json($this->payload($table), Response::HTTP_CREATED);
    }

    #[Route('/{id}', name: 'api_admin_tables_update', methods: ['PUT'])]
    public function update(TableResto $table, Request $request, EntityManagerInterface $em): JsonResponse
    {
        $data = json_decode($request->getContent(), true) ?? [];

        if (isset($data['numero'])) {
            $table->setNumero((int) $data['numero']);
        }
        if (isset($data['capacite'])) {
            $table->setCapacite((int) $data['capacite']);
        }
        if (isset($data['disponible'])) {
            $table->setDisponible((bool) $data['disponible']);
        }

        $em->flush();

        return $this->json($this->payload($table));
    }

    #[Route('/{id}', name: 'api_admin_tables_delete', methods: ['DELETE'])]
    public function delete(TableResto $table, EntityManagerInterface $em): JsonResponse
    {
        $em->remove($table);
        $em->flush();

        return $this->json(['message' => 'Table supprimée.']);
    }

    private function payload(TableResto $table): array
    {
        return [
            'id' => $table->getId(),
            'numero' => $table->getNumero(),
            'capacite' => $table->getCapacite(),
            'disponible' => $table->isDisponible(),
        ];
    }
}
