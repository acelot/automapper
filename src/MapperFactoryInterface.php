<?php declare(strict_types=1);

namespace Acelot\AutoMapper;

interface MapperFactoryInterface
{
    public function create(ContextInterface $context, FieldInterface ...$fields): MapperInterface;
}
