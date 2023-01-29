<?php declare(strict_types=1);

namespace Acelot\AutoMapper\Tests\Functional;

use Acelot\AutoMapper\AutoMapper as a;
use Acelot\AutoMapper\Context\Context;
use PHPUnit\Framework\TestCase;

final class ifEqualTest extends TestCase
{
    public function testFunction(): void
    {
        $source = [
            'id' => 10,
            'title' => 'Hello, world!',
            'tags' => ['one', 'two', 'thee'],
        ];

        $result = a::marshalArray(
            new Context(),
            $source,
            a::toKey('id_equal_10', a::pipe(
                a::get('[id]'),
                a::ifEqual(10, a::value('yes'), a::value('no')),
            )),
            a::toKey('tags_count_not_equal_4', a::pipe(
                a::get('[tags]'),
                a::call(fn($v) => count($v)),
                a::ifNotEqual(4, a::value('yes'), a::value('no'))
            )),
        );

        self::assertSame(
            [
                'id_equal_10' => 'yes',
                'tags_count_not_equal_4' => 'yes',
            ],
            $result
        );
    }
}
