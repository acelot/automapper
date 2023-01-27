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

final class toFloatTest extends TestCase
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
            toKey('int_to_float', pipe(
                get('[id]'),
                toFloat()
            )),
            toKey('float_string_to_float', pipe(
                get('[price]'),
                toFloat()
            )),
            toKey('zero_string_to_float', pipe(
                get('[netto]'),
                toFloat()
            )),
            toKey('float_zero_string_to_float', pipe(
                get('[brutto]'),
                toFloat()
            )),
            toKey('zero_to_float', pipe(
                get('[width]'),
                toFloat()
            )),
            toKey('null_to_float', pipe(
                get('[available]'),
                toFloat()
            )),
        );

        self::assertSame(
            [
                'int_to_float' => 10.0,
                'float_string_to_float' => 24.345,
                'zero_string_to_float' => 0.0,
                'float_zero_string_to_float' => 0.0,
                'zero_to_float' => 0.0,
                'null_to_float' => 0.0,
            ],
            $result
        );
    }
}
