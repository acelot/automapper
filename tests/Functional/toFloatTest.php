<?php declare(strict_types=1);

namespace Acelot\AutoMapper\Tests\Functional;

use Acelot\AutoMapper\AutoMapper as a;
use Acelot\AutoMapper\Context\Context;
use PHPUnit\Framework\TestCase;

final class toFloatTest extends TestCase
{
    public function testFunction(): void
    {
        $source = [
            'id' => 10,
            'price' => '24.345',
            'netto' => '0',
            'brutto' => '0.0',
            'width' => 0,
            'available' => null,
        ];

        $result = a::marshalArray(
            new Context(),
            $source,
            a::toKey('int_to_float', a::pipe(
                a::get('[id]'),
                a::toFloat()
            )),
            a::toKey('float_string_to_float', a::pipe(
                a::get('[price]'),
                a::toFloat()
            )),
            a::toKey('zero_string_to_float', a::pipe(
                a::get('[netto]'),
                a::toFloat()
            )),
            a::toKey('float_zero_string_to_float', a::pipe(
                a::get('[brutto]'),
                a::toFloat()
            )),
            a::toKey('zero_to_float', a::pipe(
                a::get('[width]'),
                a::toFloat()
            )),
            a::toKey('null_to_float', a::pipe(
                a::get('[available]'),
                a::toFloat()
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
