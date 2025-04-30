<?php

declare(strict_types=1);

namespace App\Event;

use App\DTO\HolidayRequestToValidateDTO;
use Symfony\Contracts\EventDispatcher\Event;

class RequestCreated extends Event
{
    public const NAME = 'request.created';

    public function __construct(private readonly HolidayRequestToValidateDTO $request)
    {
    }

    public function getRequest(): HolidayRequestToValidateDTO
    {
        return $this->request;
    }

}
