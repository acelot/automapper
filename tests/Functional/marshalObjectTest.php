<?php declare(strict_types=1);

namespace Acelot\AutoMapper\Tests\Functional;

use Acelot\AutoMapper\Context\Context;
use PHPUnit\Framework\TestCase;
use stdClass;
use function Acelot\AutoMapper\get;
use function Acelot\AutoMapper\marshalObject;
use function Acelot\AutoMapper\toProp;

final class marshalObjectTest extends TestCase
{
    public function testExample(): void
    {
        $source = [
            'id' => 10,
            'title' => 'Hello, world!',
            'tags' => ['one', 'two', 'three'],
        ];

        $result = marshalObject(
            new Context(),
            $source,
            toProp('mappedId', get('[id]')),
            toProp('mappedTitle', get('[title]')),
            toProp('mappedTags', get('[tags]')),
        );

        $expected = new stdClass();
        $expected->mappedId = 10;
        $expected->mappedTitle = 'Hello, world!';
        $expected->mappedTags = ['one', 'two', 'three'];

        self::assertEquals($expected, $result);
    }
}
