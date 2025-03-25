<?php

declare(strict_types=1);

namespace App\Enum;

enum RequestStatus: string
{
    case PENDING = 'pending';
    case APPROVED = 'approved';
    case REFUSED = 'refused';

    public function getLabel(): string
    {
        return match ($this) {
            self::APPROVED => 'Approved',
            self::PENDING => 'Pending',
            self::REFUSED => 'refused',
        };
    }

    public function getBootstrapClass(): string
    {
        return match ($this) {
            self::APPROVED => 'bg-success',
            self::PENDING => 'bg-warning text-dark',
            self::REFUSED => 'bg-danger',
        };
    }
}
