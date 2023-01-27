<?php declare(strict_types=1);

namespace Acelot\AutoMapper\Tests\Functional;

use Acelot\AutoMapper\Context\Context;
use PHPUnit\Framework\TestCase;
use function Acelot\AutoMapper\call;
use function Acelot\AutoMapper\get;
use function Acelot\AutoMapper\marshalArray;
use function Acelot\AutoMapper\pipe;
use function Acelot\AutoMapper\toKey;

final class callTest extends TestCase
{
    public function testExample(): void
    {
        $source = [
            'id' => 10,
            'title' => 'Hello, world!',
            'tags' => ['one', 'two', 'three'],
        ];

        $result = marshalArray(
            new Context(),
            $source,
            toKey('mapped_id', call(fn($v) => 50)),
            toKey('mapped_title', pipe(
                get('[title]'),
                call(fn($v) => $v . '!!')
            )),
            toKey('mapped_tags', pipe(
                get('[tags]'),
                call(fn($v) => $v[1])
            )),
        );

        self::assertSame(
            [
                'mapped_id' => 50,
                'mapped_title' => 'Hello, world!!!',
                'mapped_tags' => 'two',
            ],
            $result
        );
    }
}
