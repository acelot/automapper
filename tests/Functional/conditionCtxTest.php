<?php declare(strict_types=1);

namespace Acelot\AutoMapper\Tests\Functional;

use Acelot\AutoMapper\Context\Context;
use Acelot\AutoMapper\Context\ContextInterface;
use PHPUnit\Framework\TestCase;
use function Acelot\AutoMapper\conditionCtx;
use function Acelot\AutoMapper\get;
use function Acelot\AutoMapper\marshalArray;
use function Acelot\AutoMapper\pipe;
use function Acelot\AutoMapper\toKey;
use function Acelot\AutoMapper\value;

final class conditionCtxTest extends TestCase
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
                'id_less_than' => 10,
                'title_letters' => 13,
                'two_index' => 1,
            ]),
            $source,
            toKey('id_less_than_10', pipe(
                get('[id]'),
                conditionCtx(
                    fn(ContextInterface $c, $v) => $v < $c->get('id_less_than'),
                    value(true),
                    value(false)
                )
            )),
            toKey('title_letters_count_is_13', pipe(
                get('[title]'),
                conditionCtx(
                    fn(ContextInterface $c, $v) => strlen($v) === $c->get('title_letters'),
                    value('yes'),
                    value('no')
                )
            )),
            toKey('tags_index_1_is_two', pipe(
                get('[tags]'),
                conditionCtx(
                    fn(ContextInterface $c, $v) => $v[$c->get('two_index')] === 'two',
                    value('yes'),
                    value('no')
                )
            )),
        );

        self::assertSame(
            [
                'id_less_than_10' => false,
                'title_letters_count_is_13' => 'yes',
                'tags_index_1_is_two' => 'yes',
            ],
            $result
        );
    }
}
