<?php declare(strict_types=1);

namespace Acelot\AutoMapper\Tests\Functional;

use Acelot\AutoMapper\Context\Context;
use PHPUnit\Framework\TestCase;
use function Acelot\AutoMapper\call;
use function Acelot\AutoMapper\get;
use function Acelot\AutoMapper\ifEqual;
use function Acelot\AutoMapper\ifNotEqual;
use function Acelot\AutoMapper\marshalArray;
use function Acelot\AutoMapper\pipe;
use function Acelot\AutoMapper\toKey;
use function Acelot\AutoMapper\value;

final class ifEqualTest extends TestCase
{
    public function testExample(): void
    {
        $source = [
            'id' => 10,
            'title' => 'Hello, world!',
            'tags' => ['one', 'two', 'thee'],
        ];

        $result = marshalArray(
            new Context(),
            $source,
            toKey('id_equal_10', pipe(
                get('[id]'),
                ifEqual(10, value('yes'), value('no')),
            )),
            toKey('tags_count_not_equal_4', pipe(
                get('[tags]'),
                call(fn($v) => count($v)),
                ifNotEqual(4, value('yes'), value('no'))
            )),
        );

        self::assertSame(
            [
                'id_equal_10' => 'yes',
                'tags_count_not_equal_4' => 'yes',
            ],
            $result
        );
    }
}
