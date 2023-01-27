<?php declare(strict_types=1);

namespace Acelot\AutoMapper\Tests\Functional;

use Acelot\AutoMapper\Context\Context;
use PHPUnit\Framework\TestCase;
use function Acelot\AutoMapper\find;
use function Acelot\AutoMapper\get;
use function Acelot\AutoMapper\marshalArray;
use function Acelot\AutoMapper\pipe;
use function Acelot\AutoMapper\toKey;

final class findTest extends TestCase
{
    public function testExample(): void
    {
        $source = [
            'id' => 10,
            'title' => 'Hello, world!',
            'tags' => ['one', 'two', 'three'],
        ];

        $result = marshalArray(
            new Context(),
            $source,
            toKey('third_tag_by_key', pipe(
                get('[tags]'),
                find(fn($v, $k) => $k === 2)
            )),
            toKey('second_tag_by_value', pipe(
                get('[tags]'),
                find(fn($v, $k) => $v === 'two')
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
