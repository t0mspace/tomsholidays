<?php

declare(strict_types=1);

namespace App\Repository;

use App\DTO\HolidayRequestToValidateDTO;
use App\Entity\Employee;
use App\Entity\Request;
use App\Enum\RequestStatus;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\Exception;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @extends ServiceEntityRepository<Request>
 */
class RequestRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Request::class);
    }

    public function getRequestsByEmployee(Employee $employee): array
    {
        return $this->createQueryBuilder('request')
            ->addSelect('holidays')
            ->join('request.holidays', 'holidays')
            ->join('request.employee', 'employee')
            ->where('employee = :employee')
            ->setParameter('employee', $employee)
            ->getQuery()
            ->getResult();
    }

    public function getAllOtherRequests(?UserInterface $employee)
    {
        return $this->createQueryBuilder('request')
            ->addSelect('holidays')
            ->join('request.holidays', 'holidays')
            ->join('request.employee', 'employee')
            ->where('employee != :employee')
            ->setParameter('employee', $employee)
            ->getQuery()
            ->getResult();
    }

    public function getApprovedRequestsByEmployee(?UserInterface $employee)
    {
        return $this->createQueryBuilder('request')
            ->addSelect('request')
            ->join('request.employee', 'employee')
            ->join('request.holidays', 'holidays')
            ->where('employee = :employee')
            ->andWhere('request.status = :status')
            ->setParameter('employee', $employee)
            ->setParameter('status', RequestStatus::APPROVED)
            ->getQuery()
            ->getResult();
    }

    /**
     * @throws Exception
     * @throws \DateMalformedStringException
     */
    public function checkForDatesOverlapping(HolidayRequestToValidateDTO $requestDTO): bool
    {
        $dateStart = $requestDTO->getDateStartAsDateTime()->format('Y-m-d');
        $dateEnd = $requestDTO->getDateEndAsDateTime()->format('Y-m-d');
        $employeeEmail = $requestDTO->employeeMail;
        $conn = $this->getEntityManager()->getConnection();
        $sql = "SELECT has_holiday_overlap('{$dateStart}', '{$dateEnd}','{$employeeEmail}')";
        $result = $conn->executeQuery($sql)->fetchFirstColumn();
        return (bool) $result[0];
    }
}
