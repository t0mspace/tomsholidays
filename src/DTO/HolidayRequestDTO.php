<?php

declare(strict_types=1);

namespace App\DTO;

use App\Validator\RequestDatesOverlaping;

readonly class HolidayRequestDTO
{
    public \DateTimeImmutable $dateStart;
    public \DateTimeImmutable $dateEnd;
    public string $employeeMail;

    #[RequestDatesOverlaping]
    public array $datas;

    /**
     * @throws \DateMalformedStringException
     */
    public function __construct(array $data)
    {
        $this->dateStart = new \DateTimeImmutable($data['data']['dateStart']);
        $this->dateEnd = new \DateTimeImmutable($data['data']['dateEnd']);
        $this->employeeMail = $data['data']['user'];
        $this->datas = [
            'dateStart' => $this->dateStart,
            'dateEnd' => $this->dateEnd,
            'employee' => $this->employeeMail
        ];
    }
}
