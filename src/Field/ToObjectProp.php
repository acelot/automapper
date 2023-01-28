<?php declare(strict_types=1);

namespace Acelot\AutoMapper\Field;

use Acelot\AutoMapper\ObjectFieldInterface;
use Acelot\AutoMapper\ProcessorInterface;

final class ToObjectProp implements ObjectFieldInterface
{
    public function __construct(
        private string              $property,
        private ProcessorInterface  $processor
    ) {}

    public function getProperty(): string
    {
        return $this->property;
    }

    public function getProcessor(): ProcessorInterface
    {
        return $this->processor;
    }

    public function writeValue(mixed &$target, mixed $value): void
    {
        $target->{$this->property} = $value;
    }
}
