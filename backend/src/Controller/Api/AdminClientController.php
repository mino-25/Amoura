<?php

namespace App\Controller\Api;

use App\Entity\Enum\UserRole;
use App\Entity\Utilisateur;
use App\Repository\HistoriquePointsRepository;
use App\Repository\UtilisateurRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/admin/clients')]
class AdminClientController extends AbstractApiController
{
    #[Route('', name: 'api_admin_clients_list', methods: ['GET'])]
    public function list(UtilisateurRepository $utilisateurRepository, HistoriquePointsRepository $historiqueRepository): JsonResponse
    {
        $clients = $utilisateurRepository->findBy(['role' => UserRole::CLIENT]);

        return $this->json(array_map(
            fn (Utilisateur $client) => [
                'id' => $client->getId(),
                'nom' => $client->getNom(),
                'prenom' => $client->getPrenom(),
                'email' => $client->getEmail(),
                'membreDepuis' => $client->getCreatedAt()->format('Y-m-d'),
                'nbReservations' => $client->getReservations()->count(),
                'points' => $historiqueRepository->getSoldeActuel($client),
            ],
            $clients
        ));
    }

    #[Route('/{id}', name: 'api_admin_clients_delete', methods: ['DELETE'])]
    public function delete(Utilisateur $client, EntityManagerInterface $em): JsonResponse
    {
        // Suppression RGPD : cascade sur réservations, historique et échanges via ON DELETE CASCADE
        $em->remove($client);
        $em->flush();

        return $this->json(['message' => 'Client et données associées supprimés.']);
    }
}
