<?php declare(strict_types=1);

namespace Acelot\AutoMapper\Tests\Functional;

use Acelot\AutoMapper\Context\Context;
use Acelot\AutoMapper\Context\ContextInterface;
use PHPUnit\Framework\TestCase;
use function Acelot\AutoMapper\callCtx;
use function Acelot\AutoMapper\get;
use function Acelot\AutoMapper\marshalArray;
use function Acelot\AutoMapper\pipe;
use function Acelot\AutoMapper\toKey;

final class callCtxTest extends TestCase
{
    public function testExample(): void
    {
        $source = [
            'id' => 10,
            'title' => 'Hello, world!',
            'tags' => ['one', 'two', 'three'],
        ];

        $result = marshalArray(
            new Context([
                'multiplier' => 2,
                'prefix' => 'Aloha, ',
                'index' => 2,
            ]),
            $source,
            toKey('mapped_id', pipe(
                get('[id]'),
                callCtx(fn(ContextInterface $c, $v) => $v * $c->get('multiplier'))
            )),
            toKey('mapped_title', pipe(
                get('[title]'),
                callCtx(fn(ContextInterface $c, $v) => $c->get('prefix') . $v)
            )),
            toKey('mapped_tags', pipe(
                get('[tags]'),
                callCtx(fn(ContextInterface $c, $v) => $v[$c->get('index')])
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
