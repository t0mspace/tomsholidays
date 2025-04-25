<?php

declare(strict_types=1);

namespace App\Exceptions;

class NotEnoughHolidayException extends \Exception
{
    public function __construct(string $string)
    {
        parent::__construct($string);
    }
}
