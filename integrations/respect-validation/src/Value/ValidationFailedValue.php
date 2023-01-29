<?php declare(strict_types=1);

namespace Acelot\AutoMapper\Integrations\RespectValidation\Value;

use Acelot\AutoMapper\Value\ExceptionValueInterface;
use Respect\Validation\Exceptions\ValidationException;

final class ValidationFailedValue implements ExceptionValueInterface
{
    public function __construct(
        private ValidationException $exception
    ) {}

    public function getException(): ValidationException
    {
        return $this->exception;
    }
}
