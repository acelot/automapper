<?php declare(strict_types=1);

namespace Acelot\AutoMapper\Tests\Unit\Processor;

use Acelot\AutoMapper\Context\ContextInterface;
use Acelot\AutoMapper\Exception\NotFoundException;
use Acelot\AutoMapper\Exception\UnexpectedValueException;
use Acelot\AutoMapper\Processor\MapIterable;
use Acelot\AutoMapper\ProcessorInterface;
use Acelot\AutoMapper\Value\IgnoreValue;
use Acelot\AutoMapper\Value\NotFoundValue;
use Acelot\AutoMapper\Value\UserValue;
use Acelot\AutoMapper\ValueInterface;
use ArrayIterator;
use Iterator;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Acelot\AutoMapper\Processor\MapIterable
 */
final class MapIterableTest extends TestCase
{
    public function testProcess_NotUserValuePassed_NeverCallsProcessorProcessMethod(): void
    {
        $context = $this->createMock(ContextInterface::class);
        $subProcessor = $this->createMock(ProcessorInterface::class);
        $value = $this->createMock(ValueInterface::class);

        $processor = new MapIterable($subProcessor);

        self::assertSame($value, $processor->process($context, $value));
    }

    public function testProcess_NotIterablePassed_ThrowsUnexpectedValueException(): void
    {
        $context = $this->createMock(ContextInterface::class);
        $subProcessor = $this->createMock(ProcessorInterface::class);

        self::expectExceptionObject(new UnexpectedValueException('array|Traversable', 'asd'));

        $processor = new MapIterable($subProcessor);
        $processor->process($context, new UserValue('asd'));
    }

    public function testProcess_IteratorPassed_CallsSubProcessorForEachElement(): void
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

        $processor = new MapIterable($subProcessor);

        /** @var UserValue $result */
        $result = $processor->process($context, new UserValue(new ArrayIterator([1, 2, 3])));

        // Need to spin up the generator
        iterator_to_array($result->getValue());
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

        $processor = new MapIterable($subProcessor);

        /** @var UserValue $result */
        $result = $processor->process($context, new UserValue([1, 2, 3]));

        // Need to spin up the generator
        iterator_to_array($result->getValue());
    }

    public function testProcess_SomeElementsIgnored_ReturnsIteratorWithNotIgnoredElements(): void
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

        $processor = new MapIterable($subProcessor);

        /** @var UserValue $result */
        $result = $processor->process($context, new UserValue(new ArrayIterator([1, 2, 3])));

        self::assertInstanceOf(Iterator::class, $result->getValue());
        self::assertEquals([10, 30], iterator_to_array($result->getValue()));
    }

    public function testProcess_KeepKeysAndSomeElementsIgnored_ReturnsIteratorWithNotIgnoredElements(): void
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

        $processor = new MapIterable($subProcessor, true);

        /** @var UserValue $result */
        $result = $processor->process($context, new UserValue(new ArrayIterator([1, 2, 3])));

        self::assertInstanceOf(Iterator::class, $result->getValue());
        self::assertEquals([0 => 10, 2 => 30], iterator_to_array($result->getValue()));
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

        $processor = new MapIterable($subProcessor, true);

        self::expectExceptionObject(new NotFoundException('test'));

        /** @var UserValue $result */
        $result = $processor->process($context, new UserValue(new ArrayIterator([1, 2, 3])));

        // Need to spin up the generator
        iterator_to_array($result->getValue());
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

        $processor = new MapIterable($subProcessor, true);

        /** @var UserValue $result */
        $result = $processor->process($context, new UserValue(new ArrayIterator([1, 2, 3])));

        self::assertInstanceOf(Iterator::class, $result->getValue());
        self::assertEquals([10, 20, 30], iterator_to_array($result->getValue()));
    }
}
