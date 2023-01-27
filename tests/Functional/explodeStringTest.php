<?php declare(strict_types=1);

namespace Acelot\AutoMapper\Tests\Functional;

use Acelot\AutoMapper\Context\Context;
use PHPUnit\Framework\TestCase;
use function Acelot\AutoMapper\explodeString;
use function Acelot\AutoMapper\get;
use function Acelot\AutoMapper\ifEmpty;
use function Acelot\AutoMapper\ignore;
use function Acelot\AutoMapper\mapIterable;
use function Acelot\AutoMapper\marshalArray;
use function Acelot\AutoMapper\pipe;
use function Acelot\AutoMapper\toArray;
use function Acelot\AutoMapper\toKey;
use function Acelot\AutoMapper\trimString;

final class explodeStringTest extends TestCase
{
    public function testExample(): void
    {
        $source = [
            'id' => 10,
            'title' => 'Hello, world!',
            'tags' => 'one, two,three, ,,five,seven',
        ];

        $result = marshalArray(
            new Context(),
            $source,
            toKey('title_words', pipe(
                get('[title]'),
                explodeString(',')
            )),
            toKey('clean_tags', pipe(
                get('[tags]'),
                pipe(
                    explodeString(','),
                    mapIterable(pipe(
                        trimString(),
                        ifEmpty(ignore())
                    )),
                    toArray()
                )
            )),
        );

        self::assertSame(
            [
                'title_words' => ['Hello', ' world!'],
                'clean_tags' => ['one', 'two', 'three', 'five', 'seven'],
            ],
            $result
        );
    }
}
