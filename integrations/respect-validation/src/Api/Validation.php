<?php declare(strict_types=1);

namespace Acelot\AutoMapper\Integrations\RespectValidation\Api;

use Acelot\AutoMapper\Integrations\RespectValidation\Context\ValidationContextFactory;
use Acelot\AutoMapper\Integrations\RespectValidation\Processor\Validate;
use Acelot\AutoMapper\Integrations\RespectValidation\Value\ValidationFailedValue;
use Acelot\AutoMapper\Processor\Condition;
use Acelot\AutoMapper\Processor\Pass;
use Acelot\AutoMapper\ProcessorInterface;
use Respect\Validation\Validatable;

final class Validation
{
    public function validate(Validatable $validator): Validate
    {
        return new Validate(new ValidationContextFactory(), $validator);
    }

    public function ifValidationFailed(ProcessorInterface $true, ?ProcessorInterface $false = null): Condition
    {
        return new Condition(fn($value) => $value instanceof ValidationFailedValue, $true, $false ?? new Pass());
    }
}
