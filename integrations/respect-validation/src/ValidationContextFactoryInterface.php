<?php declare(strict_types=1);

namespace Acelot\AutoMapper\Integrations\RespectValidation;

interface ValidationContextFactoryInterface
{
    public function create(): ValidationContextInterface;
}
