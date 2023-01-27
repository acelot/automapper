<?php declare(strict_types=1);

namespace Acelot\AutoMapper\Tests\Unit\Processor;

use Acelot\AutoMapper\Context\ContextInterface;
use Acelot\AutoMapper\Exception\UnexpectedValueException;
use Acelot\AutoMapper\Processor\Find;
use Acelot\AutoMapper\Tests\Fixtures\TestGetInterface;
use Acelot\AutoMapper\Value\NotFoundValue;
use Acelot\AutoMapper\Value\UserValue;
use Acelot\AutoMapper\ValueInterface;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Acelot\AutoMapper\Processor\Find
 */
final class FindTest extends TestCase
{
    public function testProcess_PassedNotUserValue_ReturnsSameValue(): void
    {
        $context = $this->createMock(ContextInterface::class);
        $value = $this->createMock(ValueInterface::class);

        $processor = new Find(fn() => true);

        self::assertSame($value, $processor->process($context, $value));
    }

    public function testProcess_PassedNotIterableValue_ThrowsUnexpectedValueException(): void
    {
        $context = $this->createMock(ContextInterface::class);
        $processor = new Find(fn() => true);

        self::expectExceptionObject(new UnexpectedValueException('array|Traversable', 'asd'));

        $processor->process($context, new UserValue('asd'));
    }

    public function testProcess_PassedIterableValue_CallsPredicateForEachElement(): void
    {
        $context = $this->createMock(ContextInterface::class);
        $callable = $this->createMock(TestGetInterface::class);

        $processor = new Find([$callable, 'get']);

        $callable
            ->expects(self::exactly(3))
            ->method('get')
            ->withConsecutive(
                [self::equalTo(1)],
                [self::equalTo(2)],
                [self::equalTo(3)],
            );

        $processor->process($context, new UserValue([1, 2, 3]));
    }

    public function testProcess_ValueNotFound_ReturnsNotFoundValue(): void
    {
        $context = $this->createMock(ContextInterface::class);

        $processor = new Find(fn($v) => false);

        self::assertEquals(
            new NotFoundValue('by predicate'),
            $processor->process($context, new UserValue([1, 2, 3]))
        );
    }

    public function testProcess_ValueFound_ReturnsCorrectValue(): void
    {
        $context = $this->createMock(ContextInterface::class);
        $processor = new Find(fn($v) => $v === 2);

        self::assertEquals(new UserValue(2), $processor->process($context, new UserValue([1, 2, 3])));
    }

    public function testProcess_ValueFoundByKey_ReturnsCorrectValue(): void
    {
        $context = $this->createMock(ContextInterface::class);
        $processor = new Find(fn($v, $k) => $k === 'c');

        self::assertEquals(
            new UserValue(3),
            $processor->process($context, new UserValue([
                'a' => 1,
                'b' => 2,
                'c' => 3,
            ]))
        );
    }
}
