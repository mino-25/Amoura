<?php

namespace App\DataFixtures;

use App\Entity\Creneau;
use App\Entity\Enum\RecompenseType;
use App\Entity\Enum\UserRole;
use App\Entity\ProgrammeFidelite;
use App\Entity\Recompense;
use App\Entity\TableResto;
use App\Entity\Utilisateur;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    public function __construct(private readonly UserPasswordHasherInterface $passwordHasher)
    {
    }

    public function load(ObjectManager $manager): void
    {
        $admin = (new Utilisateur())
            ->setNom('Amoura')
            ->setPrenom('Admin')
            ->setEmail('admin@amoura-restaurant.fr')
            ->setRoleEnum(UserRole::ADMIN);
        $admin->setPassword($this->passwordHasher->hashPassword($admin, 'Admin1234'));
        $manager->persist($admin);

        $client = (new Utilisateur())
            ->setNom('Martin')
            ->setPrenom('Alexandre')
            ->setEmail('client@amoura-restaurant.fr')
            ->setRoleEnum(UserRole::CLIENT);
        $client->setPassword($this->passwordHasher->hashPassword($client, 'Client1234'));
        $manager->persist($client);

        // 15 tables pour une capacité totale d'environ 60 couverts
        $capacites = [2, 2, 2, 2, 4, 4, 4, 4, 4, 4, 6, 6, 6, 8, 8];
        foreach ($capacites as $index => $capacite) {
            $table = (new TableResto())
                ->setNumero($index + 1)
                ->setCapacite($capacite)
                ->setDisponible(true);
            $manager->persist($table);
        }

        // Créneaux du midi et du soir
        $horaires = [
            ['12:00', '13:30'],
            ['12:30', '14:00'],
            ['13:00', '14:30'],
            ['19:00', '20:30'],
            ['19:30', '21:00'],
            ['20:00', '21:30'],
            ['20:30', '22:00'],
        ];
        foreach ($horaires as [$debut, $fin]) {
            $creneau = (new Creneau())
                ->setHeureDebut(new \DateTimeImmutable($debut))
                ->setHeureFin(new \DateTimeImmutable($fin))
                ->setActif(true);
            $manager->persist($creneau);
        }

        $programme = (new ProgrammeFidelite())
            ->setPointsParResa(10)
            ->setSeuilRecompense(100)
            ->setActif(true);
        $manager->persist($programme);

        $recompenses = [
            ['Dessert offert', RecompenseType::DESSERT, 50],
            ['Boisson offerte', RecompenseType::BOISSON, 30],
            ['Réduction de 10 %', RecompenseType::REDUCTION, 100],
        ];
        foreach ($recompenses as [$libelle, $type, $seuil]) {
            $recompense = (new Recompense())
                ->setLibelle($libelle)
                ->setType($type)
                ->setSeuilPoints($seuil)
                ->setProgramme($programme);
            $manager->persist($recompense);
        }

        $manager->flush();
    }
}
