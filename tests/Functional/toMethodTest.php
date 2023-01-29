<?php declare(strict_types=1);

namespace Acelot\AutoMapper\Tests\Functional;

use Acelot\AutoMapper\AutoMapper as a;
use Acelot\AutoMapper\Context\Context;
use Acelot\AutoMapper\Tests\Fixtures\TestClass;
use PHPUnit\Framework\TestCase;

final class toMethodTest extends TestCase
{
    public function testFunction(): void
    {
        $source = [
            'id' => 10,
            'name' => 'Hello, world!',
            'price' => 999,
        ];

        $target = new TestClass(0, '', 0);

        a::map(
            new Context(),
            $source,
            $target,
            a::toMethod('setId', a::get('[id]')),
            a::toMethod('setName', a::get('[name]')),
            a::toMethod('setPrice', a::get('[price]')),
        );

        self::assertEquals(
            [
                'id' => 10,
                'name' => 'Hello, world!',
                'price' => 999,
            ],
            [
                'id' => $target->getId(),
                'name' => $target->getName(),
                'price' => $target->getPrice(),
            ]
        );
    }
}
