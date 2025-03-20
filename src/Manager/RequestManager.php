<?php

declare(strict_types=1);

namespace App\Manager;

use App\Entity\Holiday;
use App\Entity\Request;
use App\Enum\RequestStatus;
use App\Repository\EmployeeRepository;
use Doctrine\ORM\EntityManagerInterface;

class RequestManager
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private EmployeeRepository $employeeRepository
    ) {
    }

    /**
     * @throws \DateMalformedStringException
     */
    public function generateFromData(array $requestData): void
    {

        $holiday = new Holiday();
        $holiday->setDateStart(new \DateTimeImmutable($requestData['startDate']));
        $holiday->setDateEnd(new \DateTimeImmutable($requestData['endDate']));

        $requestEntity = new Request();
        $requestEntity->setEmployee($this->employeeRepository->findByEmail($requestData['userEmail']));
        $requestEntity->setHolidays($holiday);
        $requestEntity->setStatus(RequestStatus::PENDING);
        $requestEntity->setDate(new \DateTimeImmutable("now"));

        $this->save($requestEntity);
    }

    private function save(Request $request): void
    {
        $this->entityManager->persist($request);
        $this->entityManager->flush();
    }
}
