<?php

namespace App\Controller\Api;

use App\DTO\RegisterRequest;
use App\Entity\Enum\UserRole;
use App\Entity\Utilisateur;
use App\Repository\UtilisateurRepository;
use Doctrine\ORM\EntityManagerInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/api')]
class AuthController extends AbstractApiController
{
    #[Route('/register', name: 'api_register', methods: ['POST'])]
    public function register(
        Request $request,
        SerializerInterface $serializer,
        ValidatorInterface $validator,
        UtilisateurRepository $utilisateurRepository,
        UserPasswordHasherInterface $passwordHasher,
        JWTTokenManagerInterface $jwtManager,
        EntityManagerInterface $em,
    ): JsonResponse {
        /** @var RegisterRequest $dto */
        $dto = $serializer->deserialize($request->getContent(), RegisterRequest::class, 'json');

        $violations = $validator->validate($dto);
        if (\count($violations) > 0) {
            return $this->validationErrorResponse($violations);
        }

        if (null !== $utilisateurRepository->findOneByEmail($dto->email)) {
            return $this->json(['message' => 'Un compte existe déjà avec cet email.'], Response::HTTP_CONFLICT);
        }

        $utilisateur = (new Utilisateur())
            ->setNom($dto->nom)
            ->setPrenom($dto->prenom)
            ->setEmail($dto->email)
            ->setRoleEnum(UserRole::CLIENT);

        $utilisateur->setPassword($passwordHasher->hashPassword($utilisateur, $dto->password));

        $em->persist($utilisateur);
        $em->flush();

        return $this->json([
            'token' => $jwtManager->create($utilisateur),
            'user' => $this->userPayload($utilisateur),
        ], Response::HTTP_CREATED);
    }

    #[Route('/me', name: 'api_me', methods: ['GET'])]
    public function me(): JsonResponse
    {
        $utilisateur = $this->currentUser();

        return $this->json($this->userPayload($utilisateur));
    }

    private function userPayload(Utilisateur $utilisateur): array
    {
        return [
            'id' => $utilisateur->getId(),
            'nom' => $utilisateur->getNom(),
            'prenom' => $utilisateur->getPrenom(),
            'email' => $utilisateur->getEmail(),
            'role' => $utilisateur->getRoleEnum()->value,
        ];
    }
}
