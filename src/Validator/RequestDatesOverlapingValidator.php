<?php

namespace App\Validator;

use App\Entity\Request;
use App\Repository\RequestRepository;
use Doctrine\DBAL\Exception;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

final class RequestDatesOverlapingValidator extends ConstraintValidator
{
    public function __construct(private RequestRepository $requestRepository)
    {
    }

    /**
     * @throws Exception
     */
    public function validate(mixed $value, Constraint $constraint): void
    {
        /* @var RequestDatesOverlaping $constraint */
        $hasOverlap = $this->requestRepository->checkForDatesOverlapping($value);

        if ($hasOverlap) {
            $this->context->buildViolation($constraint->message)->addViolation();
        }
    }
}
