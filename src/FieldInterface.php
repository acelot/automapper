<?php declare(strict_types=1);

namespace Acelot\AutoMapper;

/**
 * @template T
 */
interface FieldInterface
{
    public function getProcessor(): ProcessorInterface;

    /**
     * @param-out T $target
     * @param mixed $value
     * @return void
     */
    public function writeValue(mixed &$target, mixed $value): void;
}
