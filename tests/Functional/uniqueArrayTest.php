<?php declare(strict_types=1);

namespace Acelot\AutoMapper\Tests\Functional;

use Acelot\AutoMapper\AutoMapper as a;
use Acelot\AutoMapper\Context\Context;
use PHPUnit\Framework\TestCase;

final class uniqueArrayTest extends TestCase
{
    public function testFunction(): void
    {
        $source = [
            'id' => 10,
            'title' => 'Hello, world!',
            'tags' => ['one', 'four', 'one', 'two', 'two', 'three', 'five', 'three', 'one'],
        ];

        $result = a::marshalArray(
            new Context(),
            $source,
            a::toKey('unique_tags', a::pipe(
                a::get('[tags]'),
                a::uniqueArray()
            )),
            a::toKey('unique_tags_preserve_keys', a::pipe(
                a::get('[tags]'),
                a::uniqueArray(true)
            )),
        );

        self::assertSame(
            [
                'unique_tags' => ['one', 'four', 'two', 'three', 'five'],
                'unique_tags_preserve_keys' => [0 => 'one', 1 => 'four', 3 => 'two', 5 => 'three', 6 => 'five'],
            ],
            $result
        );
    }
}
