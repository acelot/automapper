<?php declare(strict_types=1);

namespace Acelot\AutoMapper;

interface ContextInterface
{
    public function has(int|string $key): bool;

    public function get(int|string $key): mixed;

    public function set(int|string $key, mixed $value): void;
}
