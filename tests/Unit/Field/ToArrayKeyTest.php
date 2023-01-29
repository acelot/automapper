<?php declare(strict_types=1);

namespace Acelot\AutoMapper\Tests\Unit\Field;

use Acelot\AutoMapper\Field\ToArrayKey;
use Acelot\AutoMapper\ProcessorInterface;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Acelot\AutoMapper\Field\ToArrayKey
 */
final class ToArrayKeyTest extends TestCase
{
    public function testGetKey_Constructed_ReturnsCorrectValue(): void
    {
        $processor = $this->createMock(ProcessorInterface::class);
        $field = new ToArrayKey('key', $processor);

        self::assertSame('key', $field->getKey());
    }

    public function testGetProcessor_Constructed_ReturnsCorrectValue(): void
    {
        $processor = $this->createMock(ProcessorInterface::class);
        $field = new ToArrayKey('key', $processor);

        self::assertSame($processor, $field->getProcessor());
    }

    public function testWriteValue_PassedArrayAndValue_WritesValueToTarget(): void
    {
        $processor = $this->createMock(ProcessorInterface::class);
        $field = new ToArrayKey('key', $processor);

        $target = [];
        $field->writeValue($target, 'value');

        self::assertSame('value', $target['key']);
    }
}
