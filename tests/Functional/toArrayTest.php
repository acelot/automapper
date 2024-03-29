<?php declare(strict_types=1);

namespace Acelot\AutoMapper\Tests\Functional;

use Acelot\AutoMapper\AutoMapper as a;
use Acelot\AutoMapper\Context\Context;
use ArrayIterator;
use PHPUnit\Framework\TestCase;

final class toArrayTest extends TestCase
{
    public function testFunction(): void
    {
        $source = [
            'id' => 10,
            'title' => 'Hello, world!',
            'tags' => ['one', 'two', 'three'],
            'available' => null,
            'has_delivery' => true,
            'reviews' => new ArrayIterator([5, 5, 4]),
        ];

        $result = a::marshalArray(
            new Context(),
            $source,
            a::toKey('int_to_array', a::pipe(
                a::get('[id]'),
                a::toArray()
            )),
            a::toKey('string_to_array', a::pipe(
                a::get('[title]'),
                a::toArray()
            )),
            a::toKey('array_to_array', a::pipe(
                a::get('[tags]'),
                a::toArray()
            )),
            a::toKey('null_to_array', a::pipe(
                a::get('[available]'),
                a::toArray()
            )),
            a::toKey('bool_to_array', a::pipe(
                a::get('[has_delivery]'),
                a::toArray()
            )),
            a::toKey('traversable_to_array', a::pipe(
                a::get('[reviews]'),
                a::toArray()
            )),
        );

        self::assertSame(
            [
                'int_to_array' => [10],
                'string_to_array' => ['Hello, world!'],
                'array_to_array' => ['one', 'two', 'three'],
                'null_to_array' => [null],
                'bool_to_array' => [true],
                'traversable_to_array' => [5, 5, 4],
            ],
            $result
        );
    }
}
