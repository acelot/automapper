<?php declare(strict_types=1);

namespace Acelot\AutoMapper\Tests\Functional;

use Acelot\AutoMapper\AutoMapper as a;
use Acelot\AutoMapper\Context\Context;
use PHPUnit\Framework\TestCase;

final class marshalArrayTest extends TestCase
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
            a::toKey('mapped_id', a::get('[id]')),
            a::toKey('mapped_title', a::get('[title]')),
            a::toKey('mapped_tags', a::get('[tags]')),
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
