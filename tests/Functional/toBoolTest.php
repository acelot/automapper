<?php declare(strict_types=1);

namespace Acelot\AutoMapper\Tests\Functional;

use Acelot\AutoMapper\AutoMapper as a;
use Acelot\AutoMapper\Context\Context;
use PHPUnit\Framework\TestCase;

final class toBoolTest extends TestCase
{
    public function testExample(): void
    {
        $source = [
            'id' => 10,
            'parent_id' => 0,
            'title' => 'Hello, world!',
            'desc' => '',
            'tags' => ['one', 'two', 'three'],
            'attrs' => [],
            'available' => null,
        ];

        $result = a::marshalArray(
            new Context(),
            $source,
            a::toKey('int_to_bool', a::pipe(
                a::get('[id]'),
                a::toBool()
            )),
            a::toKey('zero_to_bool', a::pipe(
                a::get('[parent_id]'),
                a::toBool()
            )),
            a::toKey('string_to_bool', a::pipe(
                a::get('[title]'),
                a::toBool()
            )),
            a::toKey('empty_string_to_bool', a::pipe(
                a::get('[desc]'),
                a::toBool()
            )),
            a::toKey('array_to_bool', a::pipe(
                a::get('[tags]'),
                a::toBool()
            )),
            a::toKey('empty_array_to_bool', a::pipe(
                a::get('[attrs]'),
                a::toBool()
            )),
            a::toKey('null_to_bool', a::pipe(
                a::get('[available]'),
                a::toBool()
            )),
        );

        self::assertSame(
            [
                'int_to_bool' => true,
                'zero_to_bool' => false,
                'string_to_bool' => true,
                'empty_string_to_bool' => false,
                'array_to_bool' => true,
                'empty_array_to_bool' => false,
                'null_to_bool' => false,
            ],
            $result
        );
    }
}
