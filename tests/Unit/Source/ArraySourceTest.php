<?php declare(strict_types=1);

namespace Acelot\AutoMapper\Tests\Unit\Source;

use Acelot\AutoMapper\Source\ArraySource;
use Acelot\AutoMapper\Tests\Fixtures\ArrayAccessibleObject;
use PHPUnit\Framework\TestCase;

class ArraySourceTest extends TestCase
{
    public function testShouldFailWithNotArray()
    {
        $this->expectException(\InvalidArgumentException::class);
        new ArraySource(new \stdClass());
    }

    public function testShouldCreateWithArrayAccess()
    {
        $data = new ArrayAccessibleObject([]);
        try {
            new ArraySource($data);
        } catch (\Exception $e) {
            $this->fail();
        }

        $this->assertTrue(true);
    }

    public function testShouldReturnData()
    {
        $data = ['foo' => 'bar'];
        $source = new ArraySource($data);
        $this->assertEquals($data, $source->getData());
        $this->assertEquals($data, $source->raw());
    }

    public function testShouldCheckKeyExistence()
    {
        $data = ['foo' => 'bar'];
        $source = new ArraySource($data);
        $this->assertTrue($source->has('foo'));
        $this->assertFalse($source->has('boo'));
    }

    public function testShouldGetValue()
    {
        $data = ['foo' => 'bar'];
        $source = new ArraySource($data);
        $this->assertEquals($data['foo'], $source->get('foo'));
    }

    public function testShouldGetDefaultValue()
    {
        $data = ['foo' => 'bar'];
        $source = new ArraySource($data);
        $this->assertEquals('yahoo', $source->get('boo', 'yahoo'));
    }
}
