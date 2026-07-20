<?php

namespace App\Tests\Functional\Api;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AuthControllerTest extends WebTestCase
{
    private function cleanUpTestUser(\Symfony\Bundle\FrameworkBundle\KernelBrowser $client): void
    {
        /** @var EntityManagerInterface $em */
        $em = $client->getContainer()->get('doctrine')->getManager();
        $em->createQuery('DELETE FROM App\Entity\Utilisateur u WHERE u.email = :email')
            ->setParameter('email', 'test.register@amoura-restaurant.fr')
            ->execute();
    }

    public function testRegisterCreatesUserAndReturnsToken(): void
    {
        $client = static::createClient();
        $this->cleanUpTestUser($client);

        $client->request('POST', '/api/register', server: ['CONTENT_TYPE' => 'application/json'], content: json_encode([
            'nom' => 'Dupont',
            'prenom' => 'Marie',
            'email' => 'test.register@amoura-restaurant.fr',
            'password' => 'Password123',
        ]));

        self::assertResponseStatusCodeSame(201);
        $data = json_decode($client->getResponse()->getContent(), true);
        self::assertArrayHasKey('token', $data);
        self::assertSame('test.register@amoura-restaurant.fr', $data['user']['email']);
        self::assertSame('client', $data['user']['role']);
    }

    public function testRegisterRejectsWeakPassword(): void
    {
        $client = static::createClient();
        $this->cleanUpTestUser($client);

        $client->request('POST', '/api/register', server: ['CONTENT_TYPE' => 'application/json'], content: json_encode([
            'nom' => 'Dupont',
            'prenom' => 'Marie',
            'email' => 'test.register@amoura-restaurant.fr',
            'password' => 'short',
        ]));

        self::assertResponseStatusCodeSame(422);
    }

    public function testMeRequiresAuthentication(): void
    {
        $client = static::createClient();

        $client->request('GET', '/api/me');

        self::assertResponseStatusCodeSame(401);
    }
}
