<?php declare(strict_types=1);

namespace Acelot\AutoMapper\Field;

use Acelot\AutoMapper\ObjectFieldInterface;
use Acelot\AutoMapper\ProcessorInterface;

final class ToObjectMethod implements ObjectFieldInterface
{
    public function __construct(
        private string              $method,
        private ProcessorInterface  $processor
    ) {}

    public function getProcessor(): ProcessorInterface
    {
        return $this->processor;
    }

    public function writeValue(mixed &$target, mixed $value): void
    {
        call_user_func([$target, $this->method], $value);
    }
}
