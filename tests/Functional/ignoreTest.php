<?php declare(strict_types=1);

namespace Acelot\AutoMapper\Tests\Functional;

use Acelot\AutoMapper\AutoMapper as a;
use Acelot\AutoMapper\Context\Context;
use PHPUnit\Framework\TestCase;

final class ignoreTest extends TestCase
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
            a::toKey('id', a::get('[id]')),
            a::toKey('title', a::ignore()),
            a::toKey('tags', a::condition(fn($v) => count($v) > 2, a::ignore())),
        );

        self::assertSame(
            [
                'id' => 10,
            ],
            $result
        );
    }
}
