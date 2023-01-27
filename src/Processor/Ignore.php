<?php declare(strict_types=1);

namespace Acelot\AutoMapper\Processor;

use Acelot\AutoMapper\Context\ContextInterface;
use Acelot\AutoMapper\ProcessorInterface;
use Acelot\AutoMapper\Value\IgnoreValue;
use Acelot\AutoMapper\ValueInterface;

final class Ignore implements ProcessorInterface
{
    public function process(ContextInterface $context, ValueInterface $value): ValueInterface
    {
        return new IgnoreValue();
    }
}
