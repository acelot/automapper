<?php declare(strict_types=1);

namespace Acelot\AutoMapper\Tests\Functional;

use Acelot\AutoMapper\AutoMapper as a;
use Acelot\AutoMapper\Context\Context;
use PHPUnit\Framework\TestCase;

final class sortArrayTest extends TestCase
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
            a::toKey('tags_sorted', a::pipe(
                a::get('[tags]'),
                a::sortArray()
            )),
            a::toKey('tags_reverse_sorted', a::pipe(
                a::get('[tags]'),
                a::sortArray(true)
            )),
        );

        self::assertSame(
            [
                'tags_sorted' => ['one', 'three', 'two'],
                'tags_reverse_sorted' => ['two', 'three', 'one'],
            ],
            $result
        );
    }
}
