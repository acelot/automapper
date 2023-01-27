<?php declare(strict_types=1);

namespace Acelot\AutoMapper\Tests\Functional;

use Acelot\AutoMapper\Context\Context;
use PHPUnit\Framework\TestCase;
use function Acelot\AutoMapper\get;
use function Acelot\AutoMapper\mapIterable;
use function Acelot\AutoMapper\marshalArray;
use function Acelot\AutoMapper\marshalNestedArray;
use function Acelot\AutoMapper\pipe;
use function Acelot\AutoMapper\toArray;
use function Acelot\AutoMapper\toKey;

final class marshalNestedArrayTest extends TestCase
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
            toKey('tags_nested', pipe(
                get('[tags]'),
                mapIterable(
                    marshalNestedArray(
                        toKey('name', get('@'))
                    )
                ),
                toArray()
            )),
        );

        self::assertSame(
            [
                'tags_nested' => [
                    ['name' => 'one'],
                    ['name' => 'two'],
                    ['name' => 'three'],
                ],
            ],
            $result
        );
    }
}
