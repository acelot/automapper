<?php declare(strict_types=1);

namespace Acelot\AutoMapper\Tests\Functional;

use Acelot\AutoMapper\Context\Context;
use PHPUnit\Framework\TestCase;
use function Acelot\AutoMapper\get;
use function Acelot\AutoMapper\marshalArray;
use function Acelot\AutoMapper\pipe;
use function Acelot\AutoMapper\toBool;
use function Acelot\AutoMapper\toFloat;
use function Acelot\AutoMapper\toKey;
use function Acelot\AutoMapper\toString;

final class toStringTest extends TestCase
{
    public function testExample(): void
    {
        $source = [
            'id' => 10,
            'width' => 0,
            'netto' => 24.345,
            'available' => null,
            'has_delivery' => true,
            'desc' => new class() {
                public function __toString(): string
                {
                    return 'description';
                }
            },
        ];

        $result = marshalArray(
            new Context(),
            $source,
            toKey('int_to_string', pipe(
                get('[id]'),
                toString()
            )),
            toKey('zero_to_string', pipe(
                get('[width]'),
                toString()
            )),
            toKey('float_to_string', pipe(
                get('[netto]'),
                toString()
            )),
            toKey('null_to_string', pipe(
                get('[available]'),
                toString()
            )),
            toKey('bool_to_string', pipe(
                get('[has_delivery]'),
                toString()
            )),
            toKey('class_to_string', pipe(
                get('[desc]'),
                toString()
            )),
        );

        self::assertSame(
            [
                'int_to_string' => '10',
                'zero_to_string' => '0',
                'float_to_string' => '24.345',
                'null_to_string' => '',
                'bool_to_string' => '1',
                'class_to_string' => 'description',
            ],
            $result
        );
    }
}
