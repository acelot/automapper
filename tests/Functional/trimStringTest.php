<?php declare(strict_types=1);

namespace Acelot\AutoMapper\Tests\Functional;

use Acelot\AutoMapper\AutoMapper as a;
use Acelot\AutoMapper\Context\Context;
use PHPUnit\Framework\TestCase;

final class trimStringTest extends TestCase
{
    public function testFunction(): void
    {
        $source = [
            'id' => 10,
            'title' => ' Hello, world!   ',
            'price' => '$100',
        ];

        $result = a::marshalArray(
            new Context(),
            $source,
            a::toKey('title', a::pipe(
                a::get('[title]'),
                a::trimString()
            )),
            a::toKey('price', a::pipe(
                a::get('[price]'),
                a::trimString('$')
            )),
        );

        self::assertSame(
            [
                'title' => 'Hello, world!',
                'price' => '100',
            ],
            $result
        );
    }
}
