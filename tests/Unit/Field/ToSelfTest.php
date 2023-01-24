<?php declare(strict_types=1);

namespace Acelot\AutoMapper\Tests\Unit\Field;

use Acelot\AutoMapper\Field\ToSelf;
use Acelot\AutoMapper\ProcessorInterface;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Acelot\AutoMapper\Field\ToSelf()
 */
final class ToSelfTest extends TestCase
{
    public function testGetProcessor_Constructed_ReturnsCorrectValue(): void
    {
        $processor = $this->createMock(ProcessorInterface::class);
        $field = new ToSelf($processor);

        self::assertSame($processor, $field->getProcessor());
    }

    public function testWriteValue_PassedArrayAndValue_AssignValueToTarget(): void
    {
        $processor = $this->createMock(ProcessorInterface::class);
        $field = new ToSelf($processor);

        $target = null;
        $field->writeValue($target, 'value');

        self::assertSame('value', $target);
    }
}
