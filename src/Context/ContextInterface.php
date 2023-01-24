<?php declare(strict_types=1);

namespace Acelot\AutoMapper\Context;

interface ContextInterface
{
    public function has(string $key): bool;

    public function get(string $key): mixed;

    public function set(string $key, mixed $value): void;
}
