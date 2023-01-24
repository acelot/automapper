<?php declare(strict_types=1);

namespace Acelot\AutoMapper\Tests\Unit\Processor;

use Acelot\AutoMapper\Context\ContextInterface;
use Acelot\AutoMapper\Exception\NotFoundException;
use Acelot\AutoMapper\Exception\UnexpectedValueException;
use Acelot\AutoMapper\Processor\MapArray;
use Acelot\AutoMapper\ProcessorInterface;
use Acelot\AutoMapper\Value\IgnoreValue;
use Acelot\AutoMapper\Value\NotFoundValue;
use Acelot\AutoMapper\Value\UserValue;
use Acelot\AutoMapper\ValueInterface;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Acelot\AutoMapper\Processor\MapArray
 */
final class MapArrayTest extends TestCase
{
    public function testProcess_NotUserValuePassed_NeverCallsProcessorProcessMethod(): void
    {
        $context = $this->createMock(ContextInterface::class);
        $value = $this->createMock(ValueInterface::class);
        $subProcessor = $this->createMock(ProcessorInterface::class);

        $processor = new MapArray($subProcessor);

        self::assertSame($value, $processor->process($context, $value));
    }

    public function testProcess_NotArrayPassed_ThrowsUnexpectedValueException(): void
    {
        $context = $this->createMock(ContextInterface::class);
        $subProcessor = $this->createMock(ProcessorInterface::class);

        $processor = new MapArray($subProcessor);

        self::expectExceptionObject(new UnexpectedValueException('array', 'test'));

        $processor->process($context, new UserValue('test'));
    }

    public function testProcess_ArrayPassed_CallsSubProcessorForEachElement(): void
    {
        $context = $this->createMock(ContextInterface::class);
        $subProcessor = $this->createMock(ProcessorInterface::class);
        $subProcessor
            ->expects(self::exactly(3))
            ->method('process')
            ->withConsecutive(
                [
                    self::equalTo($context),
                    self::callback(fn(ValueInterface $v) => $v instanceof UserValue && $v->getValue() === 1)
                ],
                [
                    self::equalTo($context),
                    self::callback(fn(ValueInterface $v) => $v instanceof UserValue && $v->getValue() === 2)
                ],
                [
                    self::equalTo($context),
                    self::callback(fn(ValueInterface $v) => $v instanceof UserValue && $v->getValue() === 3)
                ]
            );

        $processor = new MapArray($subProcessor);
        $processor->process($context, new UserValue([1, 2, 3]));
    }

    public function testProcess_SomeElementsIgnored_ReturnsArrayWithNotIgnoredElements(): void
    {
        $context = $this->createMock(ContextInterface::class);

        $subProcessor = $this->createMock(ProcessorInterface::class);
        $subProcessor
            ->expects(self::exactly(3))
            ->method('process')
            ->willReturnOnConsecutiveCalls(
                new UserValue(10),
                new IgnoreValue(),
                new UserValue(30),
            );

        $processor = new MapArray($subProcessor);

        self::assertEquals(new UserValue([10, 30]), $processor->process($context, new UserValue([1, 2, 3])));
    }

    public function testProcess_KeepKeysAndSomeElementsIgnored_ReturnsArrayWithNotIgnoredElements(): void
    {
        $context = $this->createMock(ContextInterface::class);

        $subProcessor = $this->createMock(ProcessorInterface::class);
        $subProcessor
            ->expects(self::exactly(3))
            ->method('process')
            ->willReturnOnConsecutiveCalls(
                new UserValue(10),
                new IgnoreValue(),
                new UserValue(30),
            );

        $processor = new MapArray($subProcessor, true);

        self::assertEquals(new UserValue([0 => 10, 2 => 30]), $processor->process($context, new UserValue([1, 2, 3])));
    }

    public function testProcess_ProcessorReturnsNotFoundValue_ThrowsNotFoundException(): void
    {
        $context = $this->createMock(ContextInterface::class);

        $subProcessor = $this->createMock(ProcessorInterface::class);
        $subProcessor
            ->expects(self::exactly(2))
            ->method('process')
            ->willReturnOnConsecutiveCalls(
                new UserValue(10),
                new NotFoundValue('test'),
                new UserValue(30),
            );

        $processor = new MapArray($subProcessor, true);

        self::expectExceptionObject(new NotFoundException('test'));

        $processor->process($context, new UserValue([1, 2, 3]));
    }

    public function testProcess_ProcessorReturnedUserValues_ReturnsCorrectValue(): void
    {
        $context = $this->createMock(ContextInterface::class);

        $subProcessor = $this->createMock(ProcessorInterface::class);
        $subProcessor
            ->expects(self::exactly(3))
            ->method('process')
            ->willReturnOnConsecutiveCalls(
                new UserValue(10),
                new UserValue(20),
                new UserValue(30),
            );

        $processor = new MapArray($subProcessor, true);

        self::assertEquals(new UserValue([10, 20, 30]), $processor->process($context, new UserValue([1, 2, 3])));
    }
}
