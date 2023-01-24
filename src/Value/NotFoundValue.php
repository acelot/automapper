<?php declare(strict_types=1);

namespace Acelot\AutoMapper\Value;

use Acelot\AutoMapper\ValueInterface;

final class NotFoundValue implements ValueInterface
{
    public function __construct(
        private string $path
    ) {}

    public function getPath(): string
    {
        return $this->path;
    }
}
