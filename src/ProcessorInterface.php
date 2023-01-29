<?php declare(strict_types=1);

namespace Acelot\AutoMapper;

use Acelot\AutoMapper\ContextInterface;

interface ProcessorInterface
{
    public function process(ContextInterface $context, ValueInterface $value): ValueInterface;
}
