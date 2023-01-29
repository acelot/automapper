<?php declare(strict_types=1);

namespace Acelot\AutoMapper\Context;

use Acelot\AutoMapper\ContextInterface;
use OutOfBoundsException;

final class Context implements ContextInterface
{
    /**
     * @var array<int|string, mixed> $items
     */
    public function __construct(
        private array $items = []
    ) {}

    public function has(int|string $key): bool
    {
        return array_key_exists($key, $this->items);
    }

    /**
     * @template T
     * @param int|string|class-string<T> $key
     * @return mixed|T
     */
    public function get(int|string $key): mixed
    {
        if (!$this->has($key)) {
            throw new OutOfBoundsException('Key does not exists in context');
        }

        return $this->items[$key];
    }

    public function set(int|string $key, mixed $value): void
    {
        $this->items[$key] = $value;
    }
}
