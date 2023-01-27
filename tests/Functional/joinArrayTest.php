<?php declare(strict_types=1);

namespace Acelot\AutoMapper\Tests\Functional;

use Acelot\AutoMapper\AutoMapper as a;
use Acelot\AutoMapper\Context\Context;
use PHPUnit\Framework\TestCase;

final class joinArrayTest extends TestCase
{
    public function testExample(): void
    {
        $source = [
            'id' => 10,
            'title' => 'Hello, world!',
            'tags' => ['one', 'two', 'three'],
        ];

        $result = a::marshalArray(
            new Context(),
            $source,
            a::toKey('tags_by_comma', a::pipe(
                a::get('[tags]'),
                a::joinArray(', ')
            )),
        );

        self::assertSame(
            [
                'tags_by_comma' => 'one, two, three',
            ],
            $result
        );
    }
}
