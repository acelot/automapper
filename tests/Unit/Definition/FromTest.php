<?php declare(strict_types=1);

namespace Acelot\AutoMapper\Tests\Unit\Definition;

use Acelot\AutoMapper\Definition\From;
use Acelot\AutoMapper\Exception\IgnoreFieldException;
use Acelot\AutoMapper\Exception\SourceFieldMissingException;
use Acelot\AutoMapper\Source\ArraySource;
use PHPUnit\Framework\TestCase;

class FromTest extends TestCase
{
    public function testShouldGetValue()
    {
        $source = new ArraySource(['foo' => 'bar', 'boo' => 'baz']);

        $this->assertEquals('bar', From::create('foo')->getValue($source));
        $this->assertEquals('baz', From::create('boo')->getValue($source));
    }

    public function testShouldConvertValue()
    {
        $source = new ArraySource(['foo' => 'bar', 'boo' => 'baz']);
        $definition = From::create('foo')->convert('strtoupper');
        $this->assertEquals('BAR', $definition->getValue($source));
    }

    public function testShouldTrimValue()
    {
        $source = new ArraySource(['foo' => 'bar', 'boo' => ' baz ']);
        $definition = From::create('boo')->trim();
        $this->assertEquals('baz', $definition->getValue($source));
    }

    public function testShouldReturnDefaultValue()
    {
        $source = new ArraySource(['foo' => 'bar']);
        $definition = From::create('boo')->default(null);
        $this->assertNull($definition->getValue($source));
    }

    public function testShouldIgnoreEmpty()
    {
        $source = new ArraySource(['foo' => '']);
        $definition = From::create('foo')->ignoreEmpty();

        $this->expectException(IgnoreFieldException::class);
        $definition->getValue($source);
    }

    public function testShouldIgnoreMissing()
    {
        $source = new ArraySource(['foo' => 'bar']);
        $definition = From::create('boo')->ignoreMissing();

        $this->expectException(IgnoreFieldException::class);
        $definition->getValue($source);
    }

    public function testShouldThrowExceptionIfMissing()
    {
        $source = new ArraySource(['foo' => 'bar']);
        $definition = From::create('boo');

        $this->expectException(SourceFieldMissingException::class);
        $definition->getValue($source);
    }
}