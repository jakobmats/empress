<?php

declare(strict_types=1);

namespace Empress\Validation\Validator;

final class IntValidator implements ValidatorInterface
{
    public function validate(mixed $value): int
    {
        $filtered = \filter_var($value, \FILTER_VALIDATE_INT);

        if ($filtered === false) {
            throw new ValidatorException(\sprintf(
                'Value %s could not be converted to int.',
                $value
            ));
        }

        return $filtered;
    }
}
