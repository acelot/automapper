<?php declare(strict_types=1);

namespace Acelot\AutoMapper\Tests\Functional;

use Acelot\AutoMapper\AutoMapper as a;
use Acelot\AutoMapper\Context\Context;
use Acelot\AutoMapper\Exception\NotFoundException;
use PHPUnit\Framework\TestCase;

final class notFoundTest extends TestCase
{
    public function testFunction(): void
    {
        $source = [
            'id' => 10,
            'title' => 'Hello, world!',
            'tags' => ['one', 'two', 'three'],
        ];

        self::expectExceptionObject(new NotFoundException('desc'));

        a::marshalArray(
            new Context(),
            $source,
            a::toKey('id', a::get('[id]')),
            a::toKey('desc', a::notFound('desc')),
        );
    }

    public function testFunctionRecover(): void
    {
        $source = [
            'id' => 10,
            'title' => 'Hello, world!',
            'tags' => ['one', 'two', 'three'],
        ];

        $result = a::marshalArray(
            new Context(),
            $source,
            a::toKey('id', a::get('[id]')),
            a::toKey('desc', a::pipe(
                a::notFound('desc'),
                a::ifNotFound(a::value('default description'))
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
