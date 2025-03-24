<?php

declare(strict_types=1);

namespace App\Manager;

use App\Entity\Holiday;
use App\Entity\Request;
use App\Enum\RequestStatus;
use App\Repository\EmployeeRepository;
use Doctrine\ORM\EntityManagerInterface;
use PDOException;

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
        try {
            $holiday = new Holiday();
            $holiday->setDateStart(new \DateTimeImmutable($requestData['startDate']))
                ->setDateEnd(new \DateTimeImmutable($requestData['endDate']));

            $requestEntity = new Request();
            $requestEntity->setEmployee($this->employeeRepository->findByEmail($requestData['userEmail']))
                ->setHolidays($holiday)
                ->setStatus(RequestStatus::PENDING)
                ->setDate(new \DateTimeImmutable("now"));

            $this->save($requestEntity);
        } catch (PdoException $e) {
            throw new PdoException("Error while creating request" . $e->getMessage());
        }
    }

    private function save(Request $request): void
    {
        $this->entityManager->persist($request);
        $this->entityManager->flush();
    }
}
