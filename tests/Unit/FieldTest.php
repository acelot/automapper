<?php declare(strict_types=1);

namespace Acelot\AutoMapper\Tests\Unit;

use Acelot\AutoMapper\Definition\From;
use Acelot\AutoMapper\Field;
use PHPUnit\Framework\TestCase;

class FieldTest extends TestCase
{
    public function testShouldCreateField()
    {
        $definition = From::create('bar');
        $field = Field::create('foo', $definition);
        $this->assertEquals('foo', $field->getName());
        $this->assertEquals($definition, $field->getDefinition());
    }

    public function testShouldReturnNewFieldWithAnotherName()
    {
        $definition = From::create('bar');
        $field1 = Field::create('foo', $definition);
        $field2 = $field1->withName('boo');
        $this->assertNotEquals($field1, $field2);
        $this->assertEquals('boo', $field2->getName());
        $this->assertSame($definition, $field2->getDefinition());
    }

    public function testShouldReturnNewFieldWithAnotherDefinition()
    {
        $definition1 = From::create('bar');
        $field1 = Field::create('foo', $definition1);

        $definition2 = From::create('baz');
        $field2 = $field1->withDefinition($definition2);

        $this->assertEquals('foo', $field2->getName());
        $this->assertNotEquals($field1->getDefinition(), $field2->getDefinition());
        $this->assertEquals($definition2, $field2->getDefinition());
    }
}