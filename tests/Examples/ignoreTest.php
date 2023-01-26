<?php declare(strict_types=1);

namespace Acelot\AutoMapper\Tests\Examples;

use Acelot\AutoMapper\Context\Context;
use PHPUnit\Framework\TestCase;
use function Acelot\AutoMapper\condition;
use function Acelot\AutoMapper\get;
use function Acelot\AutoMapper\ignore;
use function Acelot\AutoMapper\marshalArray;
use function Acelot\AutoMapper\toKey;

final class ignoreTest extends TestCase
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
            toKey('id', get('[id]')),
            toKey('title', ignore()),
            toKey('tags', condition(fn($v) => count($v) > 2, ignore())),
        );

        self::assertSame(
            [
                'id' => 10,
            ],
            $result
        );
    }
}
