<?php declare(strict_types=1);

namespace Acelot\AutoMapper\Tests\Functional;

use Acelot\AutoMapper\AutoMapper as a;
use Acelot\AutoMapper\Context\Context;
use PHPUnit\Framework\TestCase;

final class findTest extends TestCase
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
            a::toKey('third_tag_by_key', a::pipe(
                a::get('[tags]'),
                a::find(fn($v, $k) => $k === 2)
            )),
            a::toKey('second_tag_by_value', a::pipe(
                a::get('[tags]'),
                a::find(fn($v, $k) => $v === 'two')
            )),
        );

        self::assertSame(
            [
                'third_tag_by_key' => 'three',
                'second_tag_by_value' => 'two',
            ],
            $result
        );
    }
}
