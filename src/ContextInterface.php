<?php declare(strict_types=1);

namespace Acelot\AutoMapper;

interface ContextInterface
{
    public function has(int|string $key): bool;

    /**
     * @template T
     * @param int|string|class-string<T> $key
     * @return mixed|T
     */
    public function get(int|string $key): mixed;

    public function set(int|string $key, mixed $value): void;
}
