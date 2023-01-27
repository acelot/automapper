<?php declare(strict_types=1);

namespace Acelot\AutoMapper\Tests\Functional;

use Acelot\AutoMapper\Context\Context;
use PHPUnit\Framework\TestCase;
use function Acelot\AutoMapper\get;
use function Acelot\AutoMapper\marshalArray;
use function Acelot\AutoMapper\pipe;
use function Acelot\AutoMapper\toInt;
use function Acelot\AutoMapper\toKey;

final class toIntTest extends TestCase
{
    public function testExample(): void
    {
        $source = [
            'id' => 10,
            'price' => '24.345',
            'netto' => '0',
            'brutto' => '0.0',
            'width' => 0,
            'available' => null,
        ];

        $result = marshalArray(
            new Context(),
            $source,
            toKey('int_to_int', pipe(
                get('[id]'),
                toInt()
            )),
            toKey('float_string_to_int', pipe(
                get('[price]'),
                toInt()
            )),
            toKey('zero_string_to_int', pipe(
                get('[netto]'),
                toInt()
            )),
            toKey('float_zero_string_to_int', pipe(
                get('[brutto]'),
                toInt()
            )),
            toKey('zero_to_int', pipe(
                get('[width]'),
                toInt()
            )),
            toKey('null_to_int', pipe(
                get('[available]'),
                toInt()
            )),
        );

        self::assertSame(
            [
                'int_to_int' => 10,
                'float_string_to_int' => 24,
                'zero_string_to_int' => 0,
                'float_zero_string_to_int' => 0,
                'zero_to_int' => 0,
                'null_to_int' => 0,
            ],
            $result
        );
    }
}
