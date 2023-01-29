<?php declare(strict_types=1);

namespace Acelot\AutoMapper\Tests\Functional;

use Acelot\AutoMapper\AutoMapper as a;
use Acelot\AutoMapper\Context\Context;
use PHPUnit\Framework\TestCase;

final class mapTest extends TestCase
{
    public function testFunction(): void
    {
        $source = [
            'id' => 10,
            'title' => 'Hello, world!',
            'tags' => ['one', 'two', 'three'],
        ];

        $target = [
            'type' => 'article',
        ];

        a::map(
            new Context(),
            $source,
            $target,
            a::toKey('mapped_id', a::get('[id]')),
            a::toKey('mapped_title', a::get('[title]')),
            a::toKey('mapped_tags', a::get('[tags]')),
        );

        self::assertSame(
            [
                'type' => 'article',
                'mapped_id' => 10,
                'mapped_title' => 'Hello, world!',
                'mapped_tags' => ['one', 'two', 'three'],
            ],
            $target
        );
    }
}
