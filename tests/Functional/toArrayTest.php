<?php declare(strict_types=1);

namespace Acelot\AutoMapper\Tests\Functional;

use Acelot\AutoMapper\Context\Context;
use ArrayIterator;
use PHPUnit\Framework\TestCase;
use function Acelot\AutoMapper\get;
use function Acelot\AutoMapper\marshalArray;
use function Acelot\AutoMapper\pipe;
use function Acelot\AutoMapper\toArray;
use function Acelot\AutoMapper\toKey;

final class toArrayTest extends TestCase
{
    public function testExample(): void
    {
        $source = [
            'id' => 10,
            'title' => 'Hello, world!',
            'tags' => ['one', 'two', 'three'],
            'available' => null,
            'has_delivery' => true,
            'reviews' => new ArrayIterator([5, 5, 4]),
        ];

        $result = marshalArray(
            new Context(),
            $source,
            toKey('int_to_array', pipe(
                get('[id]'),
                toArray()
            )),
            toKey('string_to_array', pipe(
                get('[title]'),
                toArray()
            )),
            toKey('array_to_array', pipe(
                get('[tags]'),
                toArray()
            )),
            toKey('null_to_array', pipe(
                get('[available]'),
                toArray()
            )),
            toKey('bool_to_array', pipe(
                get('[has_delivery]'),
                toArray()
            )),
            toKey('traversable_to_array', pipe(
                get('[reviews]'),
                toArray()
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
