<?php declare(strict_types=1);

namespace Acelot\AutoMapper\Tests\Functional;

use Acelot\AutoMapper\AutoMapper as a;
use Acelot\AutoMapper\Context\Context;
use Acelot\AutoMapper\Exception\UnexpectedValueException;
use Acelot\AutoMapper\Processor\AssertType;
use PHPUnit\Framework\TestCase;

final class assertTypeTest extends TestCase
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
            a::toKey('id', a::pipe(
                a::get('[id]'),
                a::assertType(AssertType::INT)
            )),
            a::toKey('title', a::pipe(
                a::get('[title]'),
                a::assertType(AssertType::STRING, AssertType::NULL)
            )),
            a::toKey('tags', a::pipe(
                a::get('[tags]'),
                a::assertType(AssertType::ITERABLE)
            )),
        );

        self::assertSame(
            [
                'id' => 10,
                'title' => 'Hello, world!',
                'tags' => ['one', 'two', 'three'],
            ],
            $result
        );
    }

    public function testFunctionFailAssert(): void
    {
        $source = [
            'id' => 10,
            'title' => 'Hello, world!',
            'tags' => ['one', 'two', 'three'],
        ];

        self::expectExceptionObject(new UnexpectedValueException('string|null', 10));

        a::marshalArray(
            new Context(),
            $source,
            a::toKey('id', a::pipe(
                a::get('[id]'),
                a::assertType(AssertType::STRING, AssertType::NULL)
            )),
        );
    }
}
