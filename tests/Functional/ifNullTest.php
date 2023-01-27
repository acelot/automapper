<?php declare(strict_types=1);

namespace Acelot\AutoMapper\Tests\Functional;

use Acelot\AutoMapper\AutoMapper as a;
use Acelot\AutoMapper\Context\Context;
use PHPUnit\Framework\TestCase;

final class ifNullTest extends TestCase
{
    public function testExample(): void
    {
        $source = [
            'id' => 0,
            'title' => null,
            'tags' => [],
            'desc' => '',
        ];

        $result = a::marshalArray(
            new Context(),
            $source,
            a::toKey('id', a::pipe(
                a::get('[id]'),
                a::ifNull(a::ignore()),
            )),
            a::toKey('title', a::pipe(
                a::get('[title]'),
                a::ifNull(a::value('default title')),
            )),
            a::toKey('tags', a::pipe(
                a::get('[tags]'),
                a::ifNull(a::pass()),
            )),
            a::toKey('desc', a::pipe(
                a::get('[desc]'),
                a::ifNull(a::ignore()),
            )),
        );

        self::assertSame(
            [
                'id' => 0,
                'title' => 'default title',
                'tags' => [],
                'desc' => '',
            ],
            $result
        );
    }
}
