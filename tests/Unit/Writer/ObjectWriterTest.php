<?php declare(strict_types=1);

namespace Acelot\AutoMapper\Tests\Unit\Writer;

use Acelot\AutoMapper\Writer\ObjectWriter;
use PHPUnit\Framework\TestCase;

class ObjectWriterTest extends TestCase
{
    public function testShouldWriteInObject()
    {
        $data = new \stdClass();
        $data->foo = 'bar';
        ObjectWriter::set($data, 'boo', 'baz');
        $this->assertObjectHasAttribute('boo', $data);
        $this->assertEquals('baz', $data->boo);
    }
}