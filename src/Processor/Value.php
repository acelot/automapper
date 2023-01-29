<?php declare(strict_types=1);

namespace Acelot\AutoMapper\Processor;

use Acelot\AutoMapper\ContextInterface;
use Acelot\AutoMapper\ProcessorInterface;
use Acelot\AutoMapper\Value\UserValue;
use Acelot\AutoMapper\ValueInterface;

final class Value implements ProcessorInterface
{
    public function __construct(
        private mixed $value
    ) {}

    public function process(ContextInterface $context, ValueInterface $value): ValueInterface
    {
        if ($this->value instanceof ValueInterface) {
            return $this->value;
        }

        return new UserValue($this->value);
    }
}
