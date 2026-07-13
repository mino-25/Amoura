<?php

namespace App\Controller\Api;

use App\Repository\ReservationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/profil')]
class ProfileController extends AbstractApiController
{
    #[Route('', name: 'api_profil_update', methods: ['PUT'])]
    public function update(Request $request, EntityManagerInterface $em): JsonResponse
    {
        $utilisateur = $this->currentUser();
        $data = json_decode($request->getContent(), true) ?? [];

        if (!empty($data['nom'])) {
            $utilisateur->setNom($data['nom']);
        }
        if (!empty($data['prenom'])) {
            $utilisateur->setPrenom($data['prenom']);
        }

        $em->flush();

        return $this->json([
            'id' => $utilisateur->getId(),
            'nom' => $utilisateur->getNom(),
            'prenom' => $utilisateur->getPrenom(),
            'email' => $utilisateur->getEmail(),
        ]);
    }

    #[Route('', name: 'api_profil_delete', methods: ['DELETE'])]
    public function delete(EntityManagerInterface $em): JsonResponse
    {
        // Suppression RGPD du compte : cascade sur réservations, historique et échanges
        $em->remove($this->currentUser());
        $em->flush();

        return $this->json(['message' => 'Votre compte et vos données personnelles ont été supprimés.']);
    }

    #[Route('/visites', name: 'api_profil_visites', methods: ['GET'])]
    public function visites(ReservationRepository $reservationRepository): JsonResponse
    {
        $reservations = $reservationRepository->findUpcomingForUser($this->currentUser());
        $honorees = array_filter($reservations, static fn ($r) => $r->isHonoree());

        return $this->json(['nbVisites' => \count($honorees)]);
    }
}
