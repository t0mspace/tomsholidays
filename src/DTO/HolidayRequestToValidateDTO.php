<?php

declare(strict_types=1);

namespace App\DTO;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\SerializedName;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

readonly class HolidayRequestToValidateDTO
{
    /**
     * @throws \DateMalformedStringException
     */
    public function __construct(
        #[Assert\NotBlank]
        #[Assert\Type('string')]
        #[Assert\Callback([self::class, 'validateDateStart'])]
        public string $dateStart,

        #[Assert\NotBlank]
        #[Assert\Type('string')]
        #[Assert\Callback([self::class, 'validateDateEnd'])]
        public string $dateEnd,

        #[Assert\Email()]
        #[SerializedName('employeeEmail')]
        public string $employeeMail
    ) {}

    public static function validateDateStart(string $value, ExecutionContextInterface $context): void
    {
        try {
            $dateTime = new \DateTimeImmutable($value);
        } catch (\Exception $e) {
            $context->buildViolation('Invalid date format for dateStart')
                ->addViolation();
        }
    }

    public static function validateDateEnd(string $value, ExecutionContextInterface $context): void
    {
        try {
            $dateTime = new \DateTimeImmutable($value);
        } catch (\Exception $e) {
            $context->buildViolation('Invalid date format for dateEnd')
                ->addViolation();
        }
    }

    /**
     * @throws \DateMalformedStringException
     */
    public function getDateStartAsDateTime(): \DateTimeImmutable
    {
        return new \DateTimeImmutable($this->dateStart);
    }

    /**
     * @throws \DateMalformedStringException
     */
    public function getDateEndAsDateTime(): \DateTimeImmutable
    {
        return new \DateTimeImmutable($this->dateEnd);
    }
}
