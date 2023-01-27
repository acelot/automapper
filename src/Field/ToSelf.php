<?php declare(strict_types=1);

namespace Acelot\AutoMapper\Field;

use Acelot\AutoMapper\FieldInterface;
use Acelot\AutoMapper\ProcessorInterface;

final class ToSelf implements FieldInterface
{
    public function __construct(
        private ProcessorInterface  $processor
    ) {}

    public function getProcessor(): ProcessorInterface
    {
        return $this->processor;
    }

    public function writeValue(mixed &$target, mixed $value): void
    {
        $target = $value;
    }
}
