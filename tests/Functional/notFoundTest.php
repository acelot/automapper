<?php declare(strict_types=1);

namespace Acelot\AutoMapper\Tests\Functional;

use Acelot\AutoMapper\Context\Context;
use Acelot\AutoMapper\Exception\NotFoundException;
use PHPUnit\Framework\TestCase;
use function Acelot\AutoMapper\get;
use function Acelot\AutoMapper\ifNotFound;
use function Acelot\AutoMapper\marshalArray;
use function Acelot\AutoMapper\notFound;
use function Acelot\AutoMapper\pipe;
use function Acelot\AutoMapper\toKey;
use function Acelot\AutoMapper\value;

final class notFoundTest extends TestCase
{
    public function testExample(): void
    {
        $source = [
            'id' => 10,
            'title' => 'Hello, world!',
            'tags' => ['one', 'two', 'three'],
        ];

        self::expectExceptionObject(new NotFoundException('desc'));

        $result = marshalArray(
            new Context(),
            $source,
            toKey('id', get('[id]')),
            toKey('desc', notFound('desc')),
        );
    }

    public function testExampleRecover(): void
    {
        $source = [
            'id' => 10,
            'title' => 'Hello, world!',
            'tags' => ['one', 'two', 'three'],
        ];

        $result = marshalArray(
            new Context(),
            $source,
            toKey('id', get('[id]')),
            toKey('desc', pipe(
                notFound('desc'),
                ifNotFound(value('default description'))
            )),
        );

        self::assertSame(
            [
                'id' => 10,
                'desc' => 'default description',
            ],
            $result
        );
    }
}
