<?php declare(strict_types=1);

namespace Acelot\AutoMapper\Processor;

use Acelot\AutoMapper\ContextInterface;
use Acelot\AutoMapper\ProcessorInterface;
use Acelot\AutoMapper\ValueInterface;

final class Pass implements ProcessorInterface
{
    public function process(ContextInterface $context, ValueInterface $value): ValueInterface
    {
        return $value;
    }
}
