<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;

#[\Attribute(\Attribute::TARGET_PROPERTY | \Attribute::TARGET_METHOD | \Attribute::IS_REPEATABLE)]
final class RequestDatesOverlaping extends Constraint
{
    public string $message = 'This holiday overlaps with an existing one.';

    // You can use #[HasNamedArguments] to make some constraint options required.
    // All configurable options must be passed to the constructor.
    public function __construct(
        public string $mode = 'strict',
        ?array $groups = null,
        mixed $payload = null
    ) {
        parent::__construct([], $groups, $payload);
    }
}
