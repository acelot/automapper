<?php declare(strict_types=1);

namespace Acelot\AutoMapper\Tests\Unit\Definition;

use Acelot\AutoMapper\Definition\Value;
use Acelot\AutoMapper\Exception\IgnoreFieldException;
use Acelot\AutoMapper\Source\ArraySource;
use PHPUnit\Framework\TestCase;

class ValueTest extends TestCase
{
    public function testShouldReturnValue()
    {
        $source = new ArraySource(['foo' => 'bar', 'boo' => 'baz']);
        $this->assertEquals('yahoo', Value::create('yahoo')->getValue($source));
    }

    public function testShouldTrimValue()
    {
        $source = new ArraySource(['foo' => 'bar', 'boo' => 'baz']);
        $this->assertEquals('yahoo', Value::create(' yahoo ')->trim()->getValue($source));
    }

    public function testShouldNotTrimNonStringValue()
    {
        $source = new ArraySource(['foo' => 'bar', 'boo' => 'baz']);
        $this->assertEquals(null, Value::create(null)->trim()->getValue($source));
    }

    public function testShouldIgnoreEmpty()
    {
        $source = new ArraySource(['foo' => '']);
        $definition = Value::create('')->ignoreEmpty();

        $this->expectException(IgnoreFieldException::class);
        $definition->getValue($source);
    }
}
