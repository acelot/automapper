<?php declare(strict_types=1);

namespace Acelot\AutoMapper\Tests\Functional;

use Acelot\AutoMapper\AutoMapper as a;
use Acelot\AutoMapper\Context\Context;
use PHPUnit\Framework\TestCase;

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

        $result = a::marshalArray(
            new Context(),
            $source,
            a::toKey('int_to_string', a::pipe(
                a::get('[id]'),
                a::toString()
            )),
            a::toKey('zero_to_string', a::pipe(
                a::get('[width]'),
                a::toString()
            )),
            a::toKey('float_to_string', a::pipe(
                a::get('[netto]'),
                a::toString()
            )),
            a::toKey('null_to_string', a::pipe(
                a::get('[available]'),
                a::toString()
            )),
            a::toKey('bool_to_string', a::pipe(
                a::get('[has_delivery]'),
                a::toString()
            )),
            a::toKey('class_to_string', a::pipe(
                a::get('[desc]'),
                a::toString()
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
