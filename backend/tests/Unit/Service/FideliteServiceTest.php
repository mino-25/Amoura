<?php

namespace App\Tests\Unit\Service;

use App\Entity\Enum\OperationType;
use App\Entity\Enum\RecompenseType;
use App\Entity\Enum\ReservationStatut;
use App\Entity\ProgrammeFidelite;
use App\Entity\Recompense;
use App\Entity\Reservation;
use App\Entity\Utilisateur;
use App\Exception\ReservationException;
use App\Repository\HistoriquePointsRepository;
use App\Repository\ProgrammeFideliteRepository;
use App\Service\FideliteService;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;

class FideliteServiceTest extends TestCase
{
    public function testAttributePointsAddsConfiguredAmountToCurrentBalance(): void
    {
        $programme = (new ProgrammeFidelite())->setPointsParResa(10)->setActif(true);

        $em = $this->createMock(EntityManagerInterface::class);
        $em->expects(self::once())->method('persist');
        $em->expects(self::once())->method('flush');

        $programmeRepository = $this->createStub(ProgrammeFideliteRepository::class);
        $programmeRepository->method('getSingleton')->willReturn($programme);

        $historiqueRepository = $this->createStub(HistoriquePointsRepository::class);
        $historiqueRepository->method('getSoldeActuel')->willReturn(50);

        $service = new FideliteService($em, $programmeRepository, $historiqueRepository);

        $reservation = (new Reservation())
            ->setDateReservation(new \DateTimeImmutable('tomorrow'))
            ->setNbCouverts(2)
            ->setStatut(ReservationStatut::CONFIRMEE)
            ->setUtilisateur(new Utilisateur());

        $historique = $service->attributePoints($reservation);

        self::assertSame(10, $historique->getPointsGagnes());
        self::assertSame(60, $historique->getSoldeApres());
        self::assertSame(OperationType::GAIN_RESERVATION, $historique->getTypeOperation());
    }

    public function testAttributePointsGivesZeroWhenProgrammeIsInactive(): void
    {
        $programme = (new ProgrammeFidelite())->setPointsParResa(10)->setActif(false);

        $em = $this->createStub(EntityManagerInterface::class);
        $programmeRepository = $this->createStub(ProgrammeFideliteRepository::class);
        $programmeRepository->method('getSingleton')->willReturn($programme);

        $historiqueRepository = $this->createStub(HistoriquePointsRepository::class);
        $historiqueRepository->method('getSoldeActuel')->willReturn(50);

        $service = new FideliteService($em, $programmeRepository, $historiqueRepository);

        $reservation = (new Reservation())
            ->setDateReservation(new \DateTimeImmutable('tomorrow'))
            ->setNbCouverts(2)
            ->setStatut(ReservationStatut::CONFIRMEE)
            ->setUtilisateur(new Utilisateur());

        $historique = $service->attributePoints($reservation);

        self::assertSame(0, $historique->getPointsGagnes());
        self::assertSame(50, $historique->getSoldeApres());
    }

    public function testExchangeThrowsWhenBalanceIsInsufficient(): void
    {
        $em = $this->createMock(EntityManagerInterface::class);
        $em->expects(self::never())->method('persist');

        $programmeRepository = $this->createStub(ProgrammeFideliteRepository::class);

        $historiqueRepository = $this->createStub(HistoriquePointsRepository::class);
        $historiqueRepository->method('getSoldeActuel')->willReturn(20);

        $service = new FideliteService($em, $programmeRepository, $historiqueRepository);

        $recompense = (new Recompense())
            ->setLibelle('Dessert offert')
            ->setType(RecompenseType::DESSERT)
            ->setSeuilPoints(50);

        $this->expectException(ReservationException::class);

        $service->exchange(new Utilisateur(), $recompense);
    }
}
