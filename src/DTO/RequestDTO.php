<?php declare(strict_types=1);

namespace App\DTO;

use App\Entity\Employee;

readonly class RequestDTO
{
    public \DateTimeImmutable $dateStart;
    public \DateTimeImmutable $dateEnd;
    public Employee $employee;

    /**
     * @throws \DateMalformedStringException
     */
    public function __construct(array $data)
    {
        $this->dateStart = new \DateTimeImmutable($data['date']['dateStart']);
        $this->dateEnd = new \DateTimeImmutable($data['date']['dateEnd']);
        $this->employee = $data['date']['employee'];
    }
}
