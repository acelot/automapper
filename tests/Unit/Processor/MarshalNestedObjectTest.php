<?php declare(strict_types=1);

namespace Acelot\AutoMapper\Tests\Unit\Processor;

use Acelot\AutoMapper\ContextInterface;
use Acelot\AutoMapper\Field\ToObjectProp;
use Acelot\AutoMapper\MapperFactoryInterface;
use Acelot\AutoMapper\MapperInterface;
use Acelot\AutoMapper\Processor\MarshalNestedObject;
use Acelot\AutoMapper\ProcessorInterface;
use Acelot\AutoMapper\Value\UserValue;
use Acelot\AutoMapper\ValueInterface;
use PHPUnit\Framework\TestCase;
use stdClass;

/**
 * @covers \Acelot\AutoMapper\Processor\MarshalNestedObject
 */
final class MarshalNestedObjectTest extends TestCase
{
    public function testProcess_PassedNotUserValue_ReturnsSameValue(): void
    {
        $mapperFactory = $this->createMock(MapperFactoryInterface::class);
        $context = $this->createMock(ContextInterface::class);
        $value = $this->createMock(ValueInterface::class);

        $field0Processor = $this->createMock(ProcessorInterface::class);
        $field0 = new ToObjectProp('key', $field0Processor);

        $processor = new MarshalNestedObject($mapperFactory, $field0);

        self::assertSame($value, $processor->process($context, $value));
    }

    public function testProcess_PassedUserValue_CallsMapperFactoryCreateMethod(): void
    {
        $mapperFactory = $this->createMock(MapperFactoryInterface::class);
        $context = $this->createMock(ContextInterface::class);

        $field0Processor = $this->createMock(ProcessorInterface::class);
        $field0 = new ToObjectProp('key', $field0Processor);

        $processor = new MarshalNestedObject($mapperFactory, $field0);

        $mapperFactory
            ->expects(self::once())
            ->method('create')
            ->with(self::equalTo($context), self::equalTo($field0));

        $processor->process($context, new UserValue(new stdClass()));
    }

    public function testProcess_PassedUserValue_CallsMapperMapMethod(): void
    {
        $context = $this->createMock(ContextInterface::class);

        $field0Processor = $this->createMock(ProcessorInterface::class);
        $field0 = new ToObjectProp('key', $field0Processor);

        $mapperFactory = $this->createMock(MapperFactoryInterface::class);

        $mapper = $this->createMock(MapperInterface::class);

        $mapperFactory
            ->method('create')
            ->with(self::equalTo($context), self::equalTo($field0))
            ->willReturn($mapper);

        $processor = new MarshalNestedObject($mapperFactory, $field0);

        $value = new stdClass();

        $mapper
            ->expects(self::once())
            ->method('map')
            ->with(self::equalTo($value), self::equalTo(new stdClass()));

        $processor->process($context, new UserValue($value));
    }
}
