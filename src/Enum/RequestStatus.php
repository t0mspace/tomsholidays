<?php

declare(strict_types=1);

namespace App\Enum;

enum RequestStatus: string
{
    case PENDING = 'pending';
    case APPROVED = 'approved';
    case REFUSED = 'refused';
}
