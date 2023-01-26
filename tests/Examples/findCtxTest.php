<?php declare(strict_types=1);

namespace Acelot\AutoMapper\Tests\Examples;

use Acelot\AutoMapper\Context\Context;
use Acelot\AutoMapper\Context\ContextInterface;
use PHPUnit\Framework\TestCase;
use function Acelot\AutoMapper\findCtx;
use function Acelot\AutoMapper\get;
use function Acelot\AutoMapper\marshalArray;
use function Acelot\AutoMapper\pipe;
use function Acelot\AutoMapper\toKey;

final class findCtxTest extends TestCase
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
                'index' => 0,
                'value' => 'three'
            ]),
            $source,
            toKey('tag_by_index', pipe(
                get('[tags]'),
                findCtx(fn(ContextInterface $c, $v, $k) => $k === $c->get('index'))
            )),
            toKey('tag_by_value', pipe(
                get('[tags]'),
                findCtx(fn(ContextInterface $c, $v, $k) => $v === $c->get('value'))
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
