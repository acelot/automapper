<?php declare(strict_types=1);

namespace Acelot\AutoMapper\Tests\Examples;

use Acelot\AutoMapper\Context\Context;
use PHPUnit\Framework\TestCase;
use function Acelot\AutoMapper\get;
use function Acelot\AutoMapper\marshalArray;
use function Acelot\AutoMapper\toKey;

final class marshalArrayTest extends TestCase
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
            toKey('mapped_id', get('[id]')),
            toKey('mapped_title', get('[title]')),
            toKey('mapped_tags', get('[tags]')),
        );

        self::assertSame(
            [
                'mapped_id' => 10,
                'mapped_title' => 'Hello, world!',
                'mapped_tags' => ['one', 'two', 'three'],
            ],
            $result
        );
    }
}
