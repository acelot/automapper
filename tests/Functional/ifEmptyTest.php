<?php declare(strict_types=1);

namespace Acelot\AutoMapper\Tests\Functional;

use Acelot\AutoMapper\AutoMapper as a;
use Acelot\AutoMapper\Context\Context;
use PHPUnit\Framework\TestCase;

final class ifEmptyTest extends TestCase
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
                a::ifEmpty(a::ignore()),
            )),
            a::toKey('title', a::pipe(
                a::get('[title]'),
                a::ifEmpty(a::value('default title')),
            )),
            a::toKey('tags', a::pipe(
                a::get('[tags]'),
                a::ifEmpty(a::pass()),
            )),
            a::toKey('desc', a::pipe(
                a::get('[desc]'),
                a::ifEmpty(a::ignore()),
            )),
        );

        self::assertSame(
            [
                'title' => 'default title',
                'tags' => [],
            ],
            $result
        );
    }
}
