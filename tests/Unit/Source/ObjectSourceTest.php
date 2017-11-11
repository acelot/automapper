<?php declare(strict_types=1);

namespace Acelot\AutoMapper\Tests\Unit\Source;

use Acelot\AutoMapper\Source\ObjectSource;
use PHPUnit\Framework\TestCase;

class ObjectSourceTest extends TestCase
{
    public function testShouldFailWithNotObject()
    {
        $this->expectException(\InvalidArgumentException::class);
        new ObjectSource([]);
    }

    public function testShouldReturnData()
    {
        $data = new \stdClass();
        $data->foo = 'bar';
        $data->boo = 'baz';
        $source = new ObjectSource($data);
        $this->assertEquals($data, $source->getData());
    }

    public function testShouldCheckPropertyExistence()
    {
        $data = new \stdClass();
        $data->foo = 'bar';
        $source = new ObjectSource($data);
        $this->assertTrue($source->has('foo'));
        $this->assertFalse($source->has('boo'));
    }

    public function testShouldGetValue()
    {
        $data = new \stdClass();
        $data->foo = 'bar';
        $data->boo = 'baz';
        $source = new ObjectSource($data);
        $this->assertEquals($data->foo, $source->get('foo'));
    }

    public function testShouldGetDefaultValue()
    {
        $data = new \stdClass();
        $data->foo = 'bar';
        $source = new ObjectSource($data);
        $this->assertEquals('yahoo', $source->get('boo', 'yahoo'));
    }
}