<?php declare(strict_types=1);

namespace Acelot\AutoMapper\Path\Part;

use Acelot\AutoMapper\Path\PartInterface;

final class ObjectMethod implements PartInterface
{
    public function __construct(
        private string $method
    ) {}

    public function getMethod(): string
    {
        return $this->method;
    }
}
