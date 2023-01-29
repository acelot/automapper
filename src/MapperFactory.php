<?php declare(strict_types=1);

namespace Acelot\AutoMapper;

final class MapperFactory implements MapperFactoryInterface
{
    public function create(ContextInterface $context, FieldInterface ...$fields): Mapper
    {
        return new Mapper($context, ...$fields);
    }
}
