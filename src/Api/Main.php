<?php declare(strict_types=1);

namespace Acelot\AutoMapper\Api;

use Acelot\AutoMapper\Context\ContextInterface;
use Acelot\AutoMapper\Field\ToArrayKey;
use Acelot\AutoMapper\FieldInterface;
use Acelot\AutoMapper\MapperFactory;
use Acelot\AutoMapper\MapperFactoryInterface;
use Acelot\AutoMapper\ObjectFieldInterface;
use stdClass;

final class Main
{
    private MapperFactoryInterface $mapperFactory;

    public function __construct(?MapperFactoryInterface $mapperFactory = null)
    {
        if (!$mapperFactory) {
            $mapperFactory = new MapperFactory();
        }

        $this->mapperFactory = $mapperFactory;
    }

    public function map(ContextInterface $context, mixed $source, mixed &$target, FieldInterface ...$fields): void
    {
        $mapper = $this->mapperFactory->create($context, ...$fields);
        $mapper->map($source, $target);
    }

    public function marshalArray(ContextInterface $context, mixed $source, ToArrayKey ...$fields): array
    {
        $target = [];

        $mapper = $this->mapperFactory->create($context, ...$fields);
        $mapper->map($source, $target);

        return $target;
    }

    public function marshalObject(ContextInterface $context, mixed $source, ObjectFieldInterface ...$fields): stdClass
    {
        $target = new stdClass();

        $mapper = $this->mapperFactory->create($context, ...$fields);
        $mapper->map($source, $target);

        return $target;
    }
}
