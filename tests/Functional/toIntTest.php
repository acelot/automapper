<?php declare(strict_types=1);

namespace Acelot\AutoMapper\Tests\Functional;

use Acelot\AutoMapper\AutoMapper as a;
use Acelot\AutoMapper\Context\Context;
use PHPUnit\Framework\TestCase;

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

        $result = a::marshalArray(
            new Context(),
            $source,
            a::toKey('int_to_int', a::pipe(
                a::get('[id]'),
                a::toInt()
            )),
            a::toKey('float_string_to_int', a::pipe(
                a::get('[price]'),
                a::toInt()
            )),
            a::toKey('zero_string_to_int', a::pipe(
                a::get('[netto]'),
                a::toInt()
            )),
            a::toKey('float_zero_string_to_int', a::pipe(
                a::get('[brutto]'),
                a::toInt()
            )),
            a::toKey('zero_to_int', a::pipe(
                a::get('[width]'),
                a::toInt()
            )),
            a::toKey('null_to_int', a::pipe(
                a::get('[available]'),
                a::toInt()
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
