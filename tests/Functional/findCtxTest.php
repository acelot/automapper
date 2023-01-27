<?php declare(strict_types=1);

namespace Acelot\AutoMapper\Tests\Functional;

use Acelot\AutoMapper\AutoMapper as a;
use Acelot\AutoMapper\Context\Context;
use Acelot\AutoMapper\Context\ContextInterface;
use PHPUnit\Framework\TestCase;

final class findCtxTest extends TestCase
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
                'index' => 0,
                'value' => 'three'
            ]),
            $source,
            a::toKey('tag_by_index', a::pipe(
                a::get('[tags]'),
                a::findCtx(fn(ContextInterface $c, $v, $k) => $k === $c->get('index'))
            )),
            a::toKey('tag_by_value', a::pipe(
                a::get('[tags]'),
                a::findCtx(fn(ContextInterface $c, $v, $k) => $v === $c->get('value'))
            )),
        );

        self::assertSame(
            [
                'tag_by_index' => 'one',
                'tag_by_value' => 'three',
            ],
            $result
        );
    }
}
