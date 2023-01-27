<?php declare(strict_types=1);

namespace Acelot\AutoMapper\Api;

use Acelot\AutoMapper\Context\Context;
use Acelot\AutoMapper\Field\ToArrayKey;
use Acelot\AutoMapper\FieldInterface;
use Acelot\AutoMapper\Mapper;
use Acelot\AutoMapper\ObjectFieldInterface;
use stdClass;

final class Main
{
    public function map(Context $context, mixed $source, mixed &$target, FieldInterface ...$fields): void
    {
        $mapper = new Mapper($context, ...$fields);
        $mapper->map($source, $target);
    }

    public function marshalArray(Context $context, mixed $source, ToArrayKey ...$fields): array
    {
        $target = [];

        $mapper = new Mapper($context, ...$fields);
        $mapper->map($source, $target);

        return $target;
    }

    public function marshalObject(Context $context, mixed $source, ObjectFieldInterface ...$fields): stdClass
    {
        $target = new stdClass();

        $mapper = new Mapper($context, ...$fields);
        $mapper->map($source, $target);

        return $target;
    }
}
