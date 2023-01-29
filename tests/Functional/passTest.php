<?php declare(strict_types=1);

namespace Acelot\AutoMapper\Tests\Functional;

use Acelot\AutoMapper\AutoMapper as a;
use Acelot\AutoMapper\Context\Context;
use PHPUnit\Framework\TestCase;

final class passTest extends TestCase
{
    public function testFunction(): void
    {
        $source = [
            'id' => 10,
            'title' => 'Hello, world!',
            'tags' => ['one', 'two', 'three'],
        ];

        $result = a::marshalArray(
            new Context(),
            $source,
            a::toKey('id', a::pipe(
                a::get('[id]'),
                a::condition(fn($v) => $v > 0, a::pass(), a::value('negative'))
            )),
        );

        self::assertSame(
            [
                'id' => 10,
            ],
            $result
        );
    }
}
