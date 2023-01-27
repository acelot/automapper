<?php declare(strict_types=1);

namespace Acelot\AutoMapper\Tests\Functional;

use Acelot\AutoMapper\Context\Context;
use PHPUnit\Framework\TestCase;
use function Acelot\AutoMapper\get;
use function Acelot\AutoMapper\joinArray;
use function Acelot\AutoMapper\marshalArray;
use function Acelot\AutoMapper\pipe;
use function Acelot\AutoMapper\toKey;

final class joinArrayTest extends TestCase
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
            toKey('tags_by_comma', pipe(
                get('[tags]'),
                joinArray(', ')
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
