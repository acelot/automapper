<?php declare(strict_types=1);

namespace Acelot\AutoMapper\Tests\Functional;

use Acelot\AutoMapper\Context\Context;
use PHPUnit\Framework\TestCase;
use function Acelot\AutoMapper\condition;
use function Acelot\AutoMapper\get;
use function Acelot\AutoMapper\marshalArray;
use function Acelot\AutoMapper\pipe;
use function Acelot\AutoMapper\toKey;
use function Acelot\AutoMapper\value;

final class conditionTest extends TestCase
{
    public function testExample(): void
    {
        $source = [
            'id' => 10,
            'title' => 'Hello, world!',
            'tags' => ['one', 'two', 'three'],
        ];

        $result = marshalArray(
            new Context(),
            $source,
            toKey('id_equal_10', pipe(
                get('[id]'),
                condition(
                    fn($v) => $v === 10,
                    value(true),
                    value(false)
                )
            )),
            toKey('title_is_string', pipe(
                get('[title]'),
                condition(
                    'is_string',
                    value('string'),
                    value('not string')
                )
            )),
            toKey('three_tags', pipe(
                get('[tags]'),
                condition(
                    fn($v) => count($v) === 3,
                    value('yes'),
                    value('no')
                )
            )),
        );

        self::assertSame(
            [
                'id_equal_10' => true,
                'title_is_string' => 'string',
                'three_tags' => 'yes',
            ],
            $result
        );
    }
}
