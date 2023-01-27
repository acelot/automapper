<?php declare(strict_types=1);

namespace Acelot\AutoMapper\Tests\Functional;

use Acelot\AutoMapper\Context\Context;
use PHPUnit\Framework\TestCase;
use function Acelot\AutoMapper\get;
use function Acelot\AutoMapper\mapIterable;
use function Acelot\AutoMapper\marshalArray;
use function Acelot\AutoMapper\marshalNestedObject;
use function Acelot\AutoMapper\pipe;
use function Acelot\AutoMapper\toArray;
use function Acelot\AutoMapper\toKey;
use function Acelot\AutoMapper\toProp;

final class marshalNestedObjectTest extends TestCase
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
                    marshalNestedObject(
                        toProp('name', get('@'))
                    )
                ),
                toArray()
            )),
        );

        self::assertEquals(
            [
                'tags_nested' => [
                    (object)['name' => 'one'],
                    (object)['name' => 'two'],
                    (object)['name' => 'three'],
                ],
            ],
            $result
        );
    }
}
