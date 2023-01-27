<?php declare(strict_types=1);

namespace Acelot\AutoMapper;

use Acelot\AutoMapper\Context\ContextInterface;

interface MapperFactoryInterface
{
    public function create(ContextInterface $context, FieldInterface ...$fields): MapperInterface;
}
