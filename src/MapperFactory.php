<?php declare(strict_types=1);

namespace Acelot\AutoMapper;

use Acelot\AutoMapper\Context\ContextInterface;

final class MapperFactory implements MapperFactoryInterface
{
    public function create(ContextInterface $context, FieldInterface ...$fields): Mapper
    {
        return new Mapper($context, ...$fields);
    }
}
