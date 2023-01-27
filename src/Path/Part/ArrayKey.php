<?php declare(strict_types=1);

namespace Acelot\AutoMapper\Path\Part;

use Acelot\AutoMapper\Path\PartInterface;

final class ArrayKey implements PartInterface
{
    public function __construct(
        private int|string $key
    ) {}

    public function getKey(): int|string
    {
        return $this->key;
    }
}
