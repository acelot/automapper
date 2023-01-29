<?php declare(strict_types=1);

namespace Acelot\AutoMapper\Tests\Functional;

use Acelot\AutoMapper\AutoMapper as a;
use Acelot\AutoMapper\Context\Context;
use PHPUnit\Framework\TestCase;

final class callTest extends TestCase
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
            a::toKey('mapped_id', a::call(fn($v) => 50)),
            a::toKey('mapped_title', a::pipe(
                a::get('[title]'),
                a::call(fn($v) => $v . '!!')
            )),
            a::toKey('mapped_tags', a::pipe(
                a::get('[tags]'),
                a::call(fn($v) => $v[1])
            )),
        );

        self::assertSame(
            [
                'mapped_id' => 50,
                'mapped_title' => 'Hello, world!!!',
                'mapped_tags' => 'two',
            ],
            $result
        );
    }
}
