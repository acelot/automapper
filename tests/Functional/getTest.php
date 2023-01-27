<?php declare(strict_types=1);

namespace Acelot\AutoMapper\Tests\Functional;

use Acelot\AutoMapper\AutoMapper as a;
use Acelot\AutoMapper\Context\Context;
use Acelot\AutoMapper\Tests\Fixtures\TestClass;
use PHPUnit\Framework\TestCase;

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

        $result = a::marshalArray(
            new Context(),
            $source,
            a::toKey('title_fifth_letter', a::get('[title][4]')),
            a::toKey('last_tag', a::get('[tags][#last]')),
            a::toKey('object_prop', a::get('[object]->property')),
            a::toKey('object_prop_with_spaces', a::get('[object]->{property with spaces}')),
            a::toKey('deep_class_price', a::get('[deep][test class]->getPrice()')),
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
