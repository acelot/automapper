<?php declare(strict_types=1);

namespace Acelot\AutoMapper\Field;

use Acelot\AutoMapper\FieldInterface;
use Acelot\AutoMapper\ProcessorInterface;

/**
 * @implements FieldInterface<array>
 */
final class ToArrayKey implements FieldInterface
{
    public function __construct(
        private int|string          $key,
        private ProcessorInterface  $processor
    ) {}

    public function getKey(): int|string
    {
        return $this->key;
    }

    public function getProcessor(): ProcessorInterface
    {
        return $this->processor;
    }

    public function writeValue(mixed &$target, mixed $value): void
    {
        /** @psalm-suppress MixedAssignment,MixedArrayAssignment */
        $target[$this->key] = $value;
    }
}
