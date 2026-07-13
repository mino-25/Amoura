<?php

namespace App\Tests\Functional\Api;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ReservationControllerTest extends WebTestCase
{
    public function testDisponibilitesIsPubliclyAccessible(): void
    {
        $client = static::createClient();

        $client->request('GET', '/api/reservations/disponibilites', [
            'date' => (new \DateTimeImmutable('+1 day'))->format('Y-m-d'),
            'nbCouverts' => 2,
        ]);

        self::assertResponseIsSuccessful();
        $data = json_decode($client->getResponse()->getContent(), true);
        self::assertIsArray($data);
    }

    public function testDisponibilitesRequiresDateParameter(): void
    {
        $client = static::createClient();

        $client->request('GET', '/api/reservations/disponibilites');

        self::assertResponseStatusCodeSame(400);
    }

    public function testCreateReservationRequiresAuthentication(): void
    {
        $client = static::createClient();

        $client->request('POST', '/api/reservations', server: ['CONTENT_TYPE' => 'application/json'], content: json_encode([
            'date' => (new \DateTimeImmutable('+1 day'))->format('Y-m-d'),
            'creneauId' => 1,
            'nbCouverts' => 2,
        ]));

        self::assertResponseStatusCodeSame(401);
    }
}
