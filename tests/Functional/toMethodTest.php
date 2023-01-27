<?php declare(strict_types=1);

namespace Acelot\AutoMapper\Tests\Functional;

use Acelot\AutoMapper\Context\Context;
use Acelot\AutoMapper\Tests\Fixtures\TestClass;
use PHPUnit\Framework\TestCase;
use function Acelot\AutoMapper\get;
use function Acelot\AutoMapper\map;
use function Acelot\AutoMapper\toMethod;

final class toMethodTest extends TestCase
{
    public function testExample(): void
    {
        $source = [
            'id' => 10,
            'name' => 'Hello, world!',
            'price' => 999,
        ];

        $target = new TestClass(0, '', 0);

        map(
            new Context(),
            $source,
            $target,
            toMethod('setId', get('[id]')),
            toMethod('setName', get('[name]')),
            toMethod('setPrice', get('[price]')),
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
