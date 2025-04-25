<?php

declare(strict_types=1);

namespace App\Exceptions;

class DatesOverlapingException extends \Exception
{
    public function __construct(string $string)
    {
        parent::__construct($string);
    }
}
