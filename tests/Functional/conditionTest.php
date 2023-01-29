<?php declare(strict_types=1);

namespace Acelot\AutoMapper\Tests\Functional;

use Acelot\AutoMapper\AutoMapper as a;
use Acelot\AutoMapper\Context\Context;
use PHPUnit\Framework\TestCase;

final class conditionTest extends TestCase
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
            a::toKey('id_equal_10', a::pipe(
                a::get('[id]'),
                a::condition(
                    fn($v) => $v === 10,
                    a::value(true),
                    a::value(false)
                )
            )),
            a::toKey('title_is_string', a::pipe(
                a::get('[title]'),
                a::condition(
                    'is_string',
                    a::value('string'),
                    a::value('not string')
                )
            )),
            a::toKey('three_tags', a::pipe(
                a::get('[tags]'),
                a::condition(
                    fn($v) => count($v) === 3,
                    a::value('yes'),
                    a::value('no')
                )
            )),
        );

        self::assertSame(
            [
                'id_equal_10' => true,
                'title_is_string' => 'string',
                'three_tags' => 'yes',
            ],
            $result
        );
    }
}
