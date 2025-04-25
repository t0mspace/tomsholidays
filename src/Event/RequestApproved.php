<?php

declare(strict_types=1);

namespace App\Event;

use App\Entity\Employee;
use App\Enum\RequestStatus;
use Symfony\Contracts\EventDispatcher\Event;

class RequestApproved extends Event
{
    public const NAME = 'request.approved';
    public function __construct(public string $id, public RequestStatus $status, public Employee $manager)
    {
    }

    public function getStatus(): RequestStatus
    {
        return $this->status;
    }

    public function getManager(): Employee
    {
        return $this->manager;
    }

    public function getId(): int
    {
        return (int) $this->id;
    }

}
