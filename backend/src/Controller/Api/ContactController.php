<?php

namespace App\Controller\Api;

use App\DTO\ContactRequest;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/api/contact')]
class ContactController extends AbstractApiController
{
    #[Route('', name: 'api_contact_send', methods: ['POST'])]
    public function send(
        Request $request,
        SerializerInterface $serializer,
        ValidatorInterface $validator,
        MailerInterface $mailer,
    ): JsonResponse {
        /** @var ContactRequest $dto */
        $dto = $serializer->deserialize($request->getContent(), ContactRequest::class, 'json');

        $violations = $validator->validate($dto);
        if (\count($violations) > 0) {
            return $this->validationErrorResponse($violations);
        }

        $email = (new Email())
            ->from('contact@amoura-restaurant.fr')
            ->to('contact@amoura-restaurant.fr')
            ->replyTo($dto->email)
            ->subject('Nouveau message de contact — '.$dto->prenom.' '.$dto->nom)
            ->text($dto->message);

        $mailer->send($email);

        return $this->json(['message' => 'Votre message a bien été envoyé.']);
    }
}
