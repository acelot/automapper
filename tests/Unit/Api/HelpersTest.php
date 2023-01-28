<?php declare(strict_types=1);

namespace Acelot\AutoMapper\Tests\Unit\Api;

use Acelot\AutoMapper\Api\Helpers;
use Acelot\AutoMapper\Processor\AssertType;
use Acelot\AutoMapper\Processor\Call;
use Acelot\AutoMapper\Processor\Condition;
use Acelot\AutoMapper\ProcessorInterface;
use Acelot\AutoMapper\Value\NotFoundValue;
use Acelot\AutoMapper\ValueInterface;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Acelot\AutoMapper\Api\Helpers
 */
final class HelpersTest extends TestCase
{
    public function testJoinArray_PassedArgs_ReturnsCorrectPipeline(): void
    {
        $result = (new Helpers())->joinArray();
        $processors = $result->getProcessors();

        self::assertCount(2, $processors);
        [$processor0, $processor1] = $processors;

        self::assertInstanceOf(AssertType::class, $processor0);
        self::assertSame(['array'], $processor0->getOneOfTypes());

        self::assertInstanceOf(Call::class, $processor1);
        self::assertSame('123', ($processor1->getCallable())([1, 2, 3]));
    }

    public function testSortArray_PassedArgs_ReturnsCorrectPipeline(): void
    {
        $result = (new Helpers())->sortArray();
        $processors = $result->getProcessors();

        self::assertCount(2, $processors);
        [$processor0, $processor1] = $processors;

        self::assertInstanceOf(AssertType::class, $processor0);
        self::assertSame(['array'], $processor0->getOneOfTypes());

        self::assertInstanceOf(Call::class, $processor1);
        self::assertSame([1, 2, 3], ($processor1->getCallable())([3, 2, 1]));
    }

    public function testUniqueArray_PassedArgs_ReturnsCorrectPipeline(): void
    {
        $result = (new Helpers())->uniqueArray();
        $processors = $result->getProcessors();

        self::assertCount(2, $processors);
        [$processor0, $processor1] = $processors;

        self::assertInstanceOf(AssertType::class, $processor0);
        self::assertSame(['array'], $processor0->getOneOfTypes());

        self::assertInstanceOf(Call::class, $processor1);
        self::assertSame([1, 2, 3, 4], ($processor1->getCallable())([1, 2, 2, 3, 4, 3]));
    }

    public function testIfNotFound_PassedArgs_ReturnsCorrectCondition(): void
    {
        $true = $this->createMock(ProcessorInterface::class);
        $false = $this->createMock(ProcessorInterface::class);

        $result = (new Helpers())->ifNotFound($true, $false);

        self::assertInstanceOf(Condition::class, $result);
        self::assertSame($true, $result->getTrueProcessor());
        self::assertSame($false, $result->getFalseProcessor());

        self::assertTrue(($result->getCondition())(new NotFoundValue('test')));
        self::assertFalse(($result->getCondition())($this->createMock(ValueInterface::class)));
    }
}
