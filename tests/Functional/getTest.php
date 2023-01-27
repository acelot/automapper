<?php declare(strict_types=1);

namespace Acelot\AutoMapper\Tests\Functional;

use Acelot\AutoMapper\Context\Context;
use Acelot\AutoMapper\Tests\Fixtures\TestClass;
use PHPUnit\Framework\TestCase;
use function Acelot\AutoMapper\get;
use function Acelot\AutoMapper\marshalArray;
use function Acelot\AutoMapper\toKey;

final class getTest extends TestCase
{
    public function testExample(): void
    {
        $source = [
            'id' => 10,
            'title' => 'Hello, world!',
            'tags' => ['one', 'two', 'three'],
            'object' => (object)[
                'property' => 'value',
                'property with spaces' => 'wow, value',
            ],
            'deep' => [
                'test class' => new TestClass(222, 'test class name', 500.0),
            ],
        ];

        $result = marshalArray(
            new Context(),
            $source,
            toKey('title_fifth_letter', get('[title][4]')),
            toKey('last_tag', get('[tags][#last]')),
            toKey('object_prop', get('[object]->property')),
            toKey('object_prop_with_spaces', get('[object]->{property with spaces}')),
            toKey('deep_class_price', get('[deep][test class]->getPrice()')),
        );

        self::assertSame(
            [
                'title_fifth_letter' => 'o',
                'last_tag' => 'three',
                'object_prop' => 'value',
                'object_prop_with_spaces' => 'wow, value',
                'deep_class_price' => 500.0,
            ],
            $result
        );
    }
}
