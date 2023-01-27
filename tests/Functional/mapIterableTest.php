<?php declare(strict_types=1);

namespace Acelot\AutoMapper\Tests\Functional;

use Acelot\AutoMapper\AutoMapper as a;
use Acelot\AutoMapper\Context\Context;
use PHPUnit\Framework\TestCase;

final class mapIterableTest extends TestCase
{
    public function testExample(): void
    {
        $source = [
            'id' => 10,
            'title' => 'Hello, world!',
            'tags' => ['one', 'two', 'three'],
        ];

        $result = a::marshalArray(
            new Context(),
            $source,
            a::toKey('capitalized_tags', a::pipe(
                a::get('[tags]'),
                a::mapIterable(a::call('ucfirst')),
                a::toArray()
            )),
        );

        self::assertSame(
            [
                'capitalized_tags' => ['One', 'Two', 'Three'],
            ],
            $result
        );
    }
}
