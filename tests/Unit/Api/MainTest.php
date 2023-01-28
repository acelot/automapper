<?php declare(strict_types=1);

namespace Acelot\AutoMapper\Tests\Unit\Api;

use Acelot\AutoMapper\Api\Main;
use Acelot\AutoMapper\Context\ContextInterface;
use Acelot\AutoMapper\Field\ToArrayKey;
use Acelot\AutoMapper\Field\ToObjectProp;
use Acelot\AutoMapper\FieldInterface;
use Acelot\AutoMapper\MapperFactoryInterface;
use Acelot\AutoMapper\MapperInterface;
use Acelot\AutoMapper\ProcessorInterface;
use PHPUnit\Framework\TestCase;
use stdClass;

/**
 * @covers \Acelot\AutoMapper\Api\Main
 */
final class MainTest extends TestCase
{
    public function testMap_PassedArgs_CallsMapperFactoryCreateMethod(): void
    {
        $mapperFactory = $this->createMock(MapperFactoryInterface::class);
        $context = $this->createMock(ContextInterface::class);
        $field0 = $this->createMock(FieldInterface::class);
        $field1 = $this->createMock(FieldInterface::class);

        $mapperFactory
            ->expects(self::once())
            ->method('create')
            ->with(self::equalTo($context), self::equalTo($field0), self::equalTo($field1));

        $target = [];

        (new Main($mapperFactory))->map($context, [], $target, $field0, $field1);
    }

    public function testMap_PassedArgs_CallsMapperMapMethod(): void
    {
        $mapperFactory = $this->createMock(MapperFactoryInterface::class);

        $mapper = $this->createMock(MapperInterface::class);

        $mapperFactory
            ->method('create')
            ->willReturn($mapper);

        $context = $this->createMock(ContextInterface::class);
        $field0 = $this->createMock(FieldInterface::class);
        $field1 = $this->createMock(FieldInterface::class);

        $source = ['source'];
        $target = ['target'];

        $mapper
            ->expects(self::once())
            ->method('map')
            ->with(self::equalTo($source), self::equalTo($target));

        (new Main($mapperFactory))->map($context, $source, $target, $field0, $field1);
    }

    public function testMarshalArray_PassedArgs_CallsMapperFactoryCreateMethod(): void
    {
        $mapperFactory = $this->createMock(MapperFactoryInterface::class);
        $context = $this->createMock(ContextInterface::class);
        $field0Processor = $this->createMock(ProcessorInterface::class);
        $field0 = new ToArrayKey('key0', $field0Processor);
        $field1Processor = $this->createMock(ProcessorInterface::class);
        $field1 = new ToArrayKey('key1', $field1Processor);

        $mapperFactory
            ->expects(self::once())
            ->method('create')
            ->with(self::equalTo($context), self::equalTo($field0), self::equalTo($field1));

        $source = [];

        (new Main($mapperFactory))->marshalArray($context, $source, $field0, $field1);
    }

    public function testMarshalArray_PassedArgs_CallsMapperMapMethod(): void
    {
        $mapperFactory = $this->createMock(MapperFactoryInterface::class);

        $mapper = $this->createMock(MapperInterface::class);

        $mapperFactory
            ->method('create')
            ->willReturn($mapper);

        $context = $this->createMock(ContextInterface::class);
        $field0Processor = $this->createMock(ProcessorInterface::class);
        $field0 = new ToArrayKey('key0', $field0Processor);
        $field1Processor = $this->createMock(ProcessorInterface::class);
        $field1 = new ToArrayKey('key1', $field1Processor);

        $source = ['source'];

        $mapper
            ->expects(self::once())
            ->method('map')
            ->with(self::equalTo($source), self::equalTo([]));

        (new Main($mapperFactory))->marshalArray($context, $source, $field0, $field1);
    }

    public function testMarshalObject_PassedArgs_CallsMapperFactoryCreateMethod(): void
    {
        $mapperFactory = $this->createMock(MapperFactoryInterface::class);
        $context = $this->createMock(ContextInterface::class);
        $field0Processor = $this->createMock(ProcessorInterface::class);
        $field0 = new ToObjectProp('prop0', $field0Processor);
        $field1Processor = $this->createMock(ProcessorInterface::class);
        $field1 = new ToObjectProp('prop1', $field1Processor);

        $mapperFactory
            ->expects(self::once())
            ->method('create')
            ->with(self::equalTo($context), self::equalTo($field0), self::equalTo($field1));

        $source = [];

        (new Main($mapperFactory))->marshalObject($context, $source, $field0, $field1);
    }

    public function testMarshalObject_PassedArgs_CallsMapperMapMethod(): void
    {
        $mapperFactory = $this->createMock(MapperFactoryInterface::class);

        $mapper = $this->createMock(MapperInterface::class);

        $mapperFactory
            ->method('create')
            ->willReturn($mapper);

        $context = $this->createMock(ContextInterface::class);
        $field0Processor = $this->createMock(ProcessorInterface::class);
        $field0 = new ToObjectProp('prop0', $field0Processor);
        $field1Processor = $this->createMock(ProcessorInterface::class);
        $field1 = new ToObjectProp('prop1', $field1Processor);

        $source = ['source'];

        $mapper
            ->expects(self::once())
            ->method('map')
            ->with(self::equalTo($source), self::callback(fn($v) => $v instanceof stdClass));

        (new Main($mapperFactory))->marshalObject($context, $source, $field0, $field1);
    }
}
