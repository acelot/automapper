<?php declare(strict_types=1);

namespace Acelot\AutoMapper;

interface DefinitionInterface
{
    public function getValue(mixed $source): ValueInterface;
}
