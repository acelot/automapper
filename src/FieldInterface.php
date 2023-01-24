<?php declare(strict_types=1);

namespace Acelot\AutoMapper;

interface FieldInterface
{
    public function getProcessor(): ProcessorInterface;

    public function writeValue(mixed &$target, mixed $value): void;
}
