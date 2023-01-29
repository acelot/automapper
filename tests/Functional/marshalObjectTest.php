<?php declare(strict_types=1);

namespace Acelot\AutoMapper\Tests\Functional;

use Acelot\AutoMapper\AutoMapper as a;
use Acelot\AutoMapper\Context\Context;
use PHPUnit\Framework\TestCase;
use stdClass;

final class marshalObjectTest extends TestCase
{
    public function testFunction(): void
    {
        $source = [
            'id' => 10,
            'title' => 'Hello, world!',
            'tags' => ['one', 'two', 'three'],
        ];

        $result = a::marshalObject(
            new Context(),
            $source,
            a::toProp('mappedId', a::get('[id]')),
            a::toProp('mappedTitle', a::get('[title]')),
            a::toProp('mappedTags', a::get('[tags]')),
        );

        $expected = new stdClass();
        $expected->mappedId = 10;
        $expected->mappedTitle = 'Hello, world!';
        $expected->mappedTags = ['one', 'two', 'three'];

        self::assertEquals($expected, $result);
    }
}
