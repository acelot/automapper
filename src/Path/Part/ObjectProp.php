<?php declare(strict_types=1);

namespace Acelot\AutoMapper\Path\Part;

use Acelot\AutoMapper\Path\PartInterface;

final class ObjectProp implements PartInterface
{
    public function __construct(
        private string $property
    ) {}

    public function getProperty(): string
    {
        return $this->property;
    }
}
