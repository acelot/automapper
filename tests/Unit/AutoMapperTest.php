<?php declare(strict_types=1);

namespace Acelot\AutoMapper\Tests\Unit;

use Acelot\AutoMapper\AutoMapper;
use Acelot\AutoMapper\Definition\From;
use Acelot\AutoMapper\Field;
use PHPUnit\Framework\TestCase;

class AutoMapperTest extends TestCase
{
    public function testShouldCreateMapperWithoutFields()
    {
        $mapper = AutoMapper::create();
        $this->assertCount(0, $mapper->getAllFields());
    }

    public function testShouldCreateMapperWithOneField()
    {
        $definition = From::create('bar');
        $field = Field::create('foo', $definition);
        $mapper = AutoMapper::create($field);

        $this->assertEquals($field, $mapper->getField('foo'));
        $this->assertEquals($definition, $mapper->getField('foo')->getDefinition());
    }

    public function testShouldCreateMapperWithMultipleFields()
    {
        $definition1 = From::create('bar');
        $field1 = Field::create('foo', $definition1);

        $definition2 = From::create('baz');
        $field2 = Field::create('boo', $definition2);

        $mapper = AutoMapper::create($field1, $field2);

        $this->assertEquals($field1, $mapper->getField('foo'));
        $this->assertEquals($definition1, $mapper->getField('foo')->getDefinition());
        $this->assertEquals($field2, $mapper->getField('boo'));
        $this->assertEquals($definition2, $mapper->getField('boo')->getDefinition());
    }

    public function testShouldCheckFieldExistence()
    {
        $field = Field::create('foo', From::create('bar'));
        $mapper = AutoMapper::create($field);

        $this->assertTrue($mapper->hasField('foo'));
        $this->assertFalse($mapper->hasField('bar'));
    }

    public function testShouldAddNewFieldIntoMapper()
    {
        $field1 = Field::create('foo', From::create('bar'));
        $mapper = AutoMapper::create($field1);

        $field2 = Field::create('boo', From::create('baz'));
        $mapper = $mapper->withField($field2);

        $this->assertTrue($mapper->hasField('boo'));
        $this->assertEquals($field1, $mapper->getField('foo'));
        $this->assertEquals($field2, $mapper->getField('boo'));
    }

    public function testShouldReplaceExistingField()
    {
        $definition1 = From::create('bar');
        $field1 = Field::create('foo', $definition1);
        $mapper = AutoMapper::create($field1);

        $definition2 = From::create('baz');
        $field2 = Field::create('foo', $definition2);
        $mapper = $mapper->withField($field2);

        $this->assertNotEquals($field1, $mapper->getField('foo'));
        $this->assertEquals($field2, $mapper->getField('foo'));
        $this->assertEquals($definition2, $mapper->getField('foo')->getDefinition());
    }

    public function testShouldRemoveField()
    {
        $field1 = Field::create('foo', From::create('bar'));
        $field2 = Field::create('boo', From::create('baz'));
        $mapper = AutoMapper::create($field1, $field2);
        $this->assertCount(2, $mapper->getAllFields());

        $mapper = $mapper->withoutField('boo');
        $this->assertFalse($mapper->hasField('boo'));
        $this->assertCount(1, $mapper->getAllFields());
    }

    public function testShouldMapEmptyArray()
    {
        $target = [];

        $mapper = AutoMapper::create();
        $mapper->map([], $target);

        $this->assertEmpty($target);
    }

    public function testShouldMapEmptyObject()
    {
        $target = new \stdClass();

        $mapper = AutoMapper::create();
        $mapper->map([], $target);

        $this->assertEmpty(get_object_vars($target));
    }

    public function testShouldMarshalEmptyArray()
    {
        $mapper = AutoMapper::create();
        $this->assertEmpty($mapper->marshalArray([]));
    }

    public function testShouldMarshalEmptyObject()
    {
        $mapper = AutoMapper::create();
        $this->assertEmpty(get_object_vars($mapper->marshalObject([])));
    }
}