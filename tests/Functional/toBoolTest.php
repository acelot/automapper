<?php declare(strict_types=1);

namespace Acelot\AutoMapper\Tests\Functional;

use Acelot\AutoMapper\Context\Context;
use PHPUnit\Framework\TestCase;
use function Acelot\AutoMapper\get;
use function Acelot\AutoMapper\marshalArray;
use function Acelot\AutoMapper\pipe;
use function Acelot\AutoMapper\toBool;
use function Acelot\AutoMapper\toKey;

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

        $result = marshalArray(
            new Context(),
            $source,
            toKey('int_to_bool', pipe(
                get('[id]'),
                toBool()
            )),
            toKey('zero_to_bool', pipe(
                get('[parent_id]'),
                toBool()
            )),
            toKey('string_to_bool', pipe(
                get('[title]'),
                toBool()
            )),
            toKey('empty_string_to_bool', pipe(
                get('[desc]'),
                toBool()
            )),
            toKey('array_to_bool', pipe(
                get('[tags]'),
                toBool()
            )),
            toKey('empty_array_to_bool', pipe(
                get('[attrs]'),
                toBool()
            )),
            toKey('null_to_bool', pipe(
                get('[available]'),
                toBool()
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
