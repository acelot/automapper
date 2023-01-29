<?php declare(strict_types=1);

namespace Acelot\AutoMapper\Tests\Unit;

use Acelot\AutoMapper\ContextInterface;
use Acelot\AutoMapper\Exception\NotFoundException;
use Acelot\AutoMapper\FieldInterface;
use Acelot\AutoMapper\Mapper;
use Acelot\AutoMapper\ProcessorInterface;
use Acelot\AutoMapper\Value\IgnoreValue;
use Acelot\AutoMapper\Value\NotFoundValue;
use Acelot\AutoMapper\Value\UserValue;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Acelot\AutoMapper\Mapper
 */
final class MapperTest extends TestCase
{
    public function testGetContext_Constructed_ReturnsPassedContext(): void
    {
        $context = $this->createMock(ContextInterface::class);
        $field0 = $this->createMock(FieldInterface::class);

        $mapper = new Mapper($context, $field0);

        self::assertSame($context, $mapper->getContext());
    }

    public function testMap_PassedProcessor_CallsProcessorProcessMethod(): void
    {
        $context = $this->createMock(ContextInterface::class);
        $field0 = $this->createMock(FieldInterface::class);

        $processor = $this->createMock(ProcessorInterface::class);
        $field0
            ->method('getProcessor')
            ->willReturn($processor);

        $mapper = new Mapper($context, $field0);

        $processor
            ->expects(self::once())
            ->method('process')
            ->with(self::equalTo($context), self::callback(fn($value) => $value instanceof UserValue));

        $target = [];

        $mapper->map([], $target);
    }

    public function testMap_FieldValueIsIgnoreValue_NeverCallsFieldWriterMethod(): void
    {
        $context = $this->createMock(ContextInterface::class);
        $field0 = $this->createMock(FieldInterface::class);

        $processor = $this->createMock(ProcessorInterface::class);
        $processor
            ->method('process')
            ->willReturn(new IgnoreValue());

        $field0
            ->method('getProcessor')
            ->willReturn($processor);

        $mapper = new Mapper($context, $field0);

        $field0
            ->expects(self::never())
            ->method('writeValue');

        $target = [];

        $mapper->map([], $target);
    }

    public function testMap_FieldValueIsMissingFieldValue_ThrowsFieldMissingException(): void
    {
        $context = $this->createMock(ContextInterface::class);
        $field0 = $this->createMock(FieldInterface::class);

        $field0Processor = $this->createMock(ProcessorInterface::class);
        $field0Processor
            ->method('process')
            ->willReturn(new NotFoundValue('test'));

        $field0
            ->method('getProcessor')
            ->willReturn($field0Processor);

        $mapper = new Mapper($context, $field0);

        self::expectExceptionObject(new NotFoundException('test'));

        $target = [];

        $mapper->map([], $target);
    }

    public function testMap_FieldValueIsUserValue_CallsWriterSetMethod(): void
    {
        $context = $this->createMock(ContextInterface::class);
        $field0 = $this->createMock(FieldInterface::class);
        $field0Value = new UserValue('field_value');

        $field0Processor = $this->createMock(ProcessorInterface::class);
        $field0Processor
            ->method('process')
            ->willReturn($field0Value);

        $field0
            ->method('getProcessor')
            ->willReturn($field0Processor);

        $mapper = new Mapper($context, $field0);

        $target = [];

        $field0
            ->expects(self::once())
            ->method('writeValue')
            ->with(self::equalTo($target), self::equalTo('field_value'));

        $mapper->map([], $target);
    }

    public function testGetFields_ConstructedWithoutFields_ReturnsEmptyArray(): void
    {
        $context = $this->createMock(ContextInterface::class);
        $mapper = new Mapper($context);

        self::assertEmpty($mapper->getFields());
    }

    public function testGetFields_ConstructedWithSingleField_ReturnsCorrectArray(): void
    {
        $context = $this->createMock(ContextInterface::class);
        $field0 = $this->createMock(FieldInterface::class);

        $mapper = new Mapper($context, $field0);

        self::assertSame([$field0], $mapper->getFields());
    }

    public function testGetFields_ConstructedWithMultipleField_ReturnsCorrectArray(): void
    {
        $context = $this->createMock(ContextInterface::class);
        $field0 = $this->createMock(FieldInterface::class);
        $field1 = $this->createMock(FieldInterface::class);

        $mapper = new Mapper($context, $field0, $field1);

        self::assertSame([$field0, $field1], $mapper->getFields());
    }
}
