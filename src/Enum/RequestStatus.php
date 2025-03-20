<?php

declare(strict_types=1);

namespace App\Enum;

enum RequestStatus
{
    case pending;
    case approved;
    case refused;
}
