<?php declare(strict_types=1);

namespace Acelot\AutoMapper\Tests\Functional;

use Acelot\AutoMapper\AutoMapper as a;
use Acelot\AutoMapper\Context\Context;
use Acelot\AutoMapper\Context\ContextInterface;
use PHPUnit\Framework\TestCase;

final class callCtxTest extends TestCase
{
    public function testExample(): void
    {
        $source = [
            'id' => 10,
            'title' => 'Hello, world!',
            'tags' => ['one', 'two', 'three'],
        ];

        $result = a::marshalArray(
            new Context([
                'multiplier' => 2,
                'prefix' => 'Aloha, ',
                'index' => 2,
            ]),
            $source,
            a::toKey('mapped_id', a::pipe(
                a::get('[id]'),
                a::callCtx(fn(ContextInterface $c, $v) => $v * $c->get('multiplier'))
            )),
            a::toKey('mapped_title', a::pipe(
                a::get('[title]'),
                a::callCtx(fn(ContextInterface $c, $v) => $c->get('prefix') . $v)
            )),
            a::toKey('mapped_tags', a::pipe(
                a::get('[tags]'),
                a::callCtx(fn(ContextInterface $c, $v) => $v[$c->get('index')])
            )),
        );

        self::assertSame(
            [
                'mapped_id' => 20,
                'mapped_title' => 'Aloha, Hello, world!',
                'mapped_tags' => 'three',
            ],
            $result
        );
    }
}
