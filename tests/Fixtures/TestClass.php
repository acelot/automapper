<?php

declare(strict_types=1);

namespace Acelot\AutoMapper\Tests\Fixtures;

use JsonSerializable;

final class TestClass implements JsonSerializable
{
    private int $id;

    private string $name;

    private float $price;

    public function testMethod(): string
    {
        return 'test';
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function setPrice(float $price): void
    {
        $this->price = $price;
    }

    public function jsonSerialize(): mixed
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'price' => $this->price,
        ];
    }
}
