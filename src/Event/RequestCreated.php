<?php

declare(strict_types=1);

namespace App\Event;

use App\DTO\HolidayRequestDTO;
use Symfony\Contracts\EventDispatcher\Event;

class RequestCreated extends Event
{
    public const NAME = 'request.created';

    public function __construct(private readonly HolidayRequestDTO $request)
    {
    }

    public function getRequest(): HolidayRequestDTO
    {
        return $this->request;
    }

}
