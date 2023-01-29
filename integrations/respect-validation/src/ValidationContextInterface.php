<?php declare(strict_types=1);

namespace Acelot\AutoMapper\Integrations\RespectValidation;

use Respect\Validation\Exceptions\ValidationException;

interface ValidationContextInterface
{
    public function addError(ValidationException $e): void;

    public function hasErrors(): bool;

    public function getErrors(): array;
}
