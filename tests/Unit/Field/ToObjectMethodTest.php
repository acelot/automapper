<?php declare(strict_types=1);

namespace Acelot\AutoMapper\Tests\Unit\Field;

use Acelot\AutoMapper\Field\ToObjectMethod;
use Acelot\AutoMapper\ProcessorInterface;
use Acelot\AutoMapper\Tests\Fixtures\TestSetInterface;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Acelot\AutoMapper\Field\ToObjectMethod
 */
final class ToObjectMethodTest extends TestCase
{
    public function testGetMethod_Constructed_ReturnsCorrectValue(): void
    {
        $processor = $this->createMock(ProcessorInterface::class);
        $field = new ToObjectMethod('set', $processor);

        self::assertSame('set', $field->getMethod());
    }

    public function testGetProcessor_Constructed_ReturnsCorrectValue(): void
    {
        $processor = $this->createMock(ProcessorInterface::class);
        $field = new ToObjectMethod('set', $processor);

        self::assertSame($processor, $field->getProcessor());
    }

    public function testWriteValue_PassedArrayAndValue_WritesValueToTarget(): void
    {
        $processor = $this->createMock(ProcessorInterface::class);
        $field = new ToObjectMethod('set', $processor);

        $target = $this->createMock(TestSetInterface::class);
        $target
            ->expects(self::once())
            ->method('set')
            ->with(self::equalTo('value'));

        $field->writeValue($target, 'value');
    }
}
