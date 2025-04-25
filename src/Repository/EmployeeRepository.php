<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Employee;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Employee>
 */
class EmployeeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Employee::class);
    }

    public function findByEmail(string $email): Employee
    {
        return $this->createQueryBuilder('employee')
                ->andWhere('employee.email = :email')
                ->setParameter('email', $email)
                ->getQuery()
                ->getOneOrNullResult()
        ;
    }
}
