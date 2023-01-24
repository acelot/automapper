<?php declare(strict_types=1);

namespace Acelot\AutoMapper\Value;

use Acelot\AutoMapper\ValueInterface;

final class UserValue implements ValueInterface
{
    public function __construct(
        private mixed $value
    ) {}

    public function getValue(): mixed
    {
        return $this->value;
    }
}
