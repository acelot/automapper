<?php declare(strict_types=1);

namespace Acelot\AutoMapper\Tests\Functional;

use Acelot\AutoMapper\Context\Context;
use PHPUnit\Framework\TestCase;
use function Acelot\AutoMapper\get;
use function Acelot\AutoMapper\marshalArray;
use function Acelot\AutoMapper\pipe;
use function Acelot\AutoMapper\toKey;
use function Acelot\AutoMapper\uniqueArray;

final class uniqueArrayTest extends TestCase
{
    public function testExample(): void
    {
        $source = [
            'id' => 10,
            'title' => 'Hello, world!',
            'tags' => ['one', 'four', 'one', 'two', 'two', 'three', 'five', 'three', 'one'],
        ];

        $result = marshalArray(
            new Context(),
            $source,
            toKey('unique_tags', pipe(
                get('[tags]'),
                uniqueArray()
            )),
            toKey('unique_tags_preserve_keys', pipe(
                get('[tags]'),
                uniqueArray(true)
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
