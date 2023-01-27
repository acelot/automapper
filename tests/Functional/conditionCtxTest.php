<?php declare(strict_types=1);

namespace Acelot\AutoMapper\Tests\Functional;

use Acelot\AutoMapper\AutoMapper as a;
use Acelot\AutoMapper\Context\Context;
use Acelot\AutoMapper\Context\ContextInterface;
use PHPUnit\Framework\TestCase;

final class conditionCtxTest extends TestCase
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
                'id_less_than' => 10,
                'title_letters' => 13,
                'two_index' => 1,
            ]),
            $source,
            a::toKey('id_less_than_10', a::pipe(
                a::get('[id]'),
                a::conditionCtx(
                    fn(ContextInterface $c, $v) => $v < $c->get('id_less_than'),
                    a::value(true),
                    a::value(false)
                )
            )),
            a::toKey('title_letters_count_is_13', a::pipe(
                a::get('[title]'),
                a::conditionCtx(
                    fn(ContextInterface $c, $v) => strlen($v) === $c->get('title_letters'),
                    a::value('yes'),
                    a::value('no')
                )
            )),
            a::toKey('tags_index_1_is_two', a::pipe(
                a::get('[tags]'),
                a::conditionCtx(
                    fn(ContextInterface $c, $v) => $v[$c->get('two_index')] === 'two',
                    a::value('yes'),
                    a::value('no')
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
