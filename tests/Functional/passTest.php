<?php declare(strict_types=1);

namespace Acelot\AutoMapper\Tests\Functional;

use Acelot\AutoMapper\Context\Context;
use PHPUnit\Framework\TestCase;
use function Acelot\AutoMapper\condition;
use function Acelot\AutoMapper\get;
use function Acelot\AutoMapper\marshalArray;
use function Acelot\AutoMapper\pass;
use function Acelot\AutoMapper\pipe;
use function Acelot\AutoMapper\toKey;
use function Acelot\AutoMapper\value;

final class passTest extends TestCase
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
            toKey('id', pipe(
                get('[id]'),
                condition(fn($v) => $v > 0, pass(), value('negative'))
            )),
        );

        self::assertSame(
            [
                'id' => 10,
            ],
            $result
        );
    }
}
