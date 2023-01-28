<?php declare(strict_types=1);

namespace Acelot\AutoMapper\Tests\Unit\Api;

use Acelot\AutoMapper\Api\Fields;
use Acelot\AutoMapper\Field\ToArrayKey;
use Acelot\AutoMapper\Field\ToObjectMethod;
use Acelot\AutoMapper\Field\ToObjectProp;
use Acelot\AutoMapper\Field\ToSelf;
use Acelot\AutoMapper\ProcessorInterface;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Acelot\AutoMapper\Api\Fields
 */
final class FieldsTest extends TestCase
{
    public function testToKey_PassedArgs_ReturnsToArrayKey(): void
    {
        $processor = $this->createMock(ProcessorInterface::class);

        $result = (new Fields())->toKey('key', $processor);

        self::assertInstanceOf(ToArrayKey::class, $result);
        self::assertSame('key', $result->getKey());
        self::assertSame($processor, $result->getProcessor());
    }

    public function testToProp_PassedArgs_ReturnsToObjectProp(): void
    {
        $processor = $this->createMock(ProcessorInterface::class);

        $result = (new Fields())->toProp('prop', $processor);

        self::assertInstanceOf(ToObjectProp::class, $result);
        self::assertSame('prop', $result->getProperty());
        self::assertSame($processor, $result->getProcessor());
    }

    public function testToMethod_PassedArgs_ReturnsToObjectMethod(): void
    {
        $processor = $this->createMock(ProcessorInterface::class);

        $result = (new Fields())->toMethod('method', $processor);

        self::assertInstanceOf(ToObjectMethod::class, $result);
        self::assertSame('method', $result->getMethod());
        self::assertSame($processor, $result->getProcessor());
    }

    public function testToSelf_PassedArgs_ReturnsToSelf(): void
    {
        $processor = $this->createMock(ProcessorInterface::class);

        $result = (new Fields())->toSelf($processor);

        self::assertInstanceOf(ToSelf::class, $result);
        self::assertSame($processor, $result->getProcessor());
    }
}
