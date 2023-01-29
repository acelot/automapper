<?php declare(strict_types=1);

namespace Acelot\AutoMapper\Tests\Unit\Field;

use Acelot\AutoMapper\Field\ToObjectProp;
use Acelot\AutoMapper\ProcessorInterface;
use PHPUnit\Framework\TestCase;
use stdClass;

/**
 * @covers \Acelot\AutoMapper\Field\ToObjectProp()
 */
final class ToObjectPropTest extends TestCase
{
    public function testGetProperty_Constructed_ReturnsCorrectValue(): void
    {
        $processor = $this->createMock(ProcessorInterface::class);
        $field = new ToObjectProp('prop', $processor);

        self::assertSame('prop', $field->getProperty());
    }

    public function testGetProcessor_Constructed_ReturnsCorrectValue(): void
    {
        $processor = $this->createMock(ProcessorInterface::class);
        $field = new ToObjectProp('prop', $processor);

        self::assertSame($processor, $field->getProcessor());
    }

    public function testWriteValue_PassedArrayAndValue_WritesValueToTarget(): void
    {
        $processor = $this->createMock(ProcessorInterface::class);
        $field = new ToObjectProp('prop', $processor);

        $target = new stdClass();
        $field->writeValue($target, 'value');

        self::assertSame('value', $target->prop);
    }
}
