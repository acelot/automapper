<?php declare(strict_types=1);

namespace Acelot\AutoMapper\Tests\Functional;

use Acelot\AutoMapper\AutoMapper as a;
use Acelot\AutoMapper\Context\Context;
use PHPUnit\Framework\TestCase;

final class marshalNestedArrayTest extends TestCase
{
    public function testFunction(): void
    {
        $source = [
            'id' => 10,
            'title' => 'Hello, world!',
            'tags' => ['one', 'two', 'three'],
        ];

        $result = a::marshalArray(
            new Context(),
            $source,
            a::toKey('tags_nested', a::pipe(
                a::get('[tags]'),
                a::mapIterable(
                    a::marshalNestedArray(
                        a::toKey('name', a::get('@'))
                    )
                ),
                a::toArray()
            )),
        );

        self::assertSame(
            [
                'tags_nested' => [
                    ['name' => 'one'],
                    ['name' => 'two'],
                    ['name' => 'three'],
                ],
            ],
            $result
        );
    }
}
