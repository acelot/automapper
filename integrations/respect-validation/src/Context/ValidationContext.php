<?php declare(strict_types=1);

namespace Acelot\AutoMapper\Integrations\RespectValidation\Context;

use Acelot\AutoMapper\Integrations\RespectValidation\ValidationContextInterface;
use Respect\Validation\Exceptions\ValidationException;

final class ValidationContext implements ValidationContextInterface
{
    /**
     * @var ValidationException[]
     */
    private array $errors = [];

    public function addError(ValidationException $e): void
    {
        $this->errors[] = $e;
    }

    public function hasErrors(): bool
    {
        return count($this->errors) > 0;
    }

    public function getErrors(): array
    {
        return $this->errors;
    }
}
