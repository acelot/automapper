<?php declare(strict_types=1);

namespace Acelot\AutoMapper\Integrations\RespectValidation\Context;

use Acelot\AutoMapper\Integrations\RespectValidation\ValidationContextFactoryInterface;

final class ValidationContextFactory implements ValidationContextFactoryInterface
{
    public function create(): ValidationContext
    {
        return new ValidationContext();
    }
}
