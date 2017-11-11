<?php declare(strict_types=1);

namespace Acelot\AutoMapper\Tests\Unit\Writer;

use Acelot\AutoMapper\Writer\ArrayWriter;
use PHPUnit\Framework\TestCase;

class ArrayWriterTest extends TestCase
{
    public function testShouldWriteInArray()
    {
        $data = ['foo' => 'bar'];
        ArrayWriter::set($data, 'boo', 'baz');
        $this->assertArrayHasKey('boo', $data);
        $this->assertEquals('baz', $data['boo']);
    }
}