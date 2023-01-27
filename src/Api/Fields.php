<?php declare(strict_types=1);

namespace Acelot\AutoMapper\Api;

use Acelot\AutoMapper\Field\ToArrayKey;
use Acelot\AutoMapper\Field\ToObjectMethod;
use Acelot\AutoMapper\Field\ToObjectProp;
use Acelot\AutoMapper\Field\ToSelf;
use Acelot\AutoMapper\ProcessorInterface;

final class Fields
{
    public function toKey(int|string $key, ProcessorInterface $processor): ToArrayKey
    {
        return new ToArrayKey($key, $processor);
    }

    public function toProp(string $property, ProcessorInterface $processor): ToObjectProp
    {
        return new ToObjectProp($property, $processor);
    }

    public function toMethod(string $method, ProcessorInterface $processor): ToObjectMethod
    {
        return new ToObjectMethod($method, $processor);
    }

    public function toSelf(ProcessorInterface $processor): ToSelf
    {
        return new ToSelf($processor);
    }
}
