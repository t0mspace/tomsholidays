<?php

namespace App\Repository;

use App\Entity\Employee;
use App\Entity\Request;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
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

    //    /**
    //     * @return Request[] Returns an array of Request objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('r')
    //            ->andWhere('r.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('r.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Request
    //    {
    //        return $this->createQueryBuilder('r')
    //            ->andWhere('r.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
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
}
