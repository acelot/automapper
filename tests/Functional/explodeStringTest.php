<?php declare(strict_types=1);

namespace Acelot\AutoMapper\Tests\Functional;

use Acelot\AutoMapper\AutoMapper as a;
use Acelot\AutoMapper\Context\Context;
use PHPUnit\Framework\TestCase;

final class explodeStringTest extends TestCase
{
    public function testExample(): void
    {
        $source = [
            'id' => 10,
            'title' => 'Hello, world!',
            'tags' => 'one, two,three, ,,five,seven',
        ];

        $result = a::marshalArray(
            new Context(),
            $source,
            a::toKey('title_words', a::pipe(
                a::get('[title]'),
                a::explodeString(',')
            )),
            a::toKey('clean_tags', a::pipe(
                a::get('[tags]'),
                a::pipe(
                    a::explodeString(','),
                    a::mapIterable(a::pipe(
                        a::trimString(),
                        a::ifEmpty(a::ignore())
                    )),
                    a::toArray()
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
