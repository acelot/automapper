<?php declare(strict_types=1);

namespace Acelot\AutoMapper\Tests\Functional;

use Acelot\AutoMapper\Context\Context;
use PHPUnit\Framework\TestCase;
use function Acelot\AutoMapper\get;
use function Acelot\AutoMapper\marshalArray;
use function Acelot\AutoMapper\pipe;
use function Acelot\AutoMapper\toKey;
use function Acelot\AutoMapper\trimString;

final class trimStringTest extends TestCase
{
    public function testExample(): void
    {
        $source = [
            'id' => 10,
            'title' => ' Hello, world!   ',
            'price' => '$100',
        ];

        $result = marshalArray(
            new Context(),
            $source,
            toKey('title', pipe(
                get('[title]'),
                trimString()
            )),
            toKey('price', pipe(
                get('[price]'),
                trimString('$')
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
