<?php declare(strict_types=1);

namespace Acelot\AutoMapper;

interface ProcessorInterface
{
    public function process(ContextInterface $context, ValueInterface $value): ValueInterface;
}
