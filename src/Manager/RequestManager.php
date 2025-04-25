<?php

declare(strict_types=1);

namespace App\Manager;

use App\Entity\Holiday;
use App\Entity\Request;
use App\Enum\RequestStatus;
use App\Event\RequestApproved;
use App\Event\RequestCreated;
use App\Exceptions\NotEnoughHolidayException;
use App\Exceptions\RequestNotFoundException;
use App\Repository\EmployeeRepository;
use App\Repository\RequestRepository;
use Doctrine\ORM\EntityManagerInterface;
use PDOException;

class RequestManager
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private EmployeeRepository $employeeRepository,
        private RequestRepository $requestRepository,
    ) {
    }

    /**
     * @throws \DateMalformedStringException
     */
    public function submitForApprobation(RequestCreated $requestCreated): void
    {
        try {
            $holiday = new Holiday();
            $holiday
                ->setDateStart($requestCreated->getRequest()->dateStart)
                ->setDateEnd($requestCreated->getRequest()->dateEnd);


            $requestEntity = new Request();
            $requestEntity
                ->setEmployee($this->employeeRepository->findByEmail($requestCreated->getRequest()->employeeMail))
                ->setHolidays($holiday)
                ->setStatus(RequestStatus::PENDING)
                ->setDate(new \DateTimeImmutable("now"));

            $this->save($requestEntity);

        } catch (PdoException $e) {
            throw new PdoException("Error while creating request" . $e->getMessage());
        }
    }

    /**
     * @throws NotEnoughHolidayException
     * @throws RequestNotFoundException
     */
    public function approve(RequestApproved $event): void
    {
        try {
            $request = $this->requestRepository->findOneBy(['id' => $event->getId()]);
            if ($request === null) {
                throw new RequestNotFoundException("No request found with the id" .$event->getId());
            }


            $request->setStatus(RequestStatus::APPROVED)
                ->setManagedBy($event->getManager())
                ->setDateManaged(new \DateTimeImmutable("now"));

            $employee = $request->getEmployee();


            $this->entityManager->flush();
        } catch (PDOException $e) {
            throw new PdoException("Error while approving request" . $e->getMessage());
        } catch (NotEnoughHolidayException $e) {
            throw new NotEnoughHolidayException("Error not enough holidays" . $e->getMessage());
        }
    }

    private function save(Request $request): void
    {
        $this->entityManager->persist($request);
        $this->entityManager->flush();
    }
}
