<?php

declare(strict_types=1);

namespace App\Event;

use App\Repository\EmployeeRepository;
use Symfony\Contracts\EventDispatcher\Event;

class RequestCreated extends Event
{
    public const NAME = 'request.created';

    public function __construct(public string $startDate, public string $endDate, public string $userEmail)
    {
    }

    public function getStartDate(): string
    {
        return $this->startDate;
    }

    public function getEndDate(): string
    {
        return $this->endDate;
    }

    public function getUserEmail(): string
    {
        return $this->userEmail;
    }

    public function getData(): array{
        return [
            'startDate' => $this->startDate,
            'endDate' => $this->endDate,
            'userEmail' => $this->userEmail,
        ];
    }




}
