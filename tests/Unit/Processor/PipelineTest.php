<?php declare(strict_types=1);

namespace Acelot\AutoMapper\Tests\Unit\Processor;

use Acelot\AutoMapper\Context\ContextInterface;
use Acelot\AutoMapper\Processor\Pipeline;
use Acelot\AutoMapper\ProcessorInterface;
use Acelot\AutoMapper\ValueInterface;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Acelot\AutoMapper\Processor\Pipeline
 */
final class PipelineTest extends TestCase
{
    public function testGetProcessors_Constructed_ReturnCorrectProcessors(): void
    {
        $processor0 = $this->createMock(ProcessorInterface::class);
        $processor1 = $this->createMock(ProcessorInterface::class);

        $processor = new Pipeline($processor0, $processor1);

        self::assertSame([$processor0, $processor1], $processor->getProcessors());
    }

    public function testProcess_PassedProcessors_CallsProcessorsProcessMethod(): void
    {
        $context = $this->createMock(ContextInterface::class);
        $value = $this->createMock(ValueInterface::class);
        $processor0 = $this->createMock(ProcessorInterface::class);
        $processor1 = $this->createMock(ProcessorInterface::class);

        $processor = new Pipeline($processor0, $processor1);

        $processor0Value = $this->createMock(ValueInterface::class);

        $processor0
            ->expects(self::once())
            ->method('process')
            ->with(self::equalTo($context), self::equalTo($value))
            ->willReturn($processor0Value);

        $processor0
            ->expects(self::once())
            ->method('process')
            ->with(self::equalTo($context), self::equalTo($processor0Value));

        $processor->process($context, $value);
    }

    public function testProcess_PassedProcessors_ReturnsLastProcessorValue(): void
    {
        $context = $this->createMock(ContextInterface::class);
        $value = $this->createMock(ValueInterface::class);

        $processor0 = $this->createMock(ProcessorInterface::class);

        $processor0Value = $this->createMock(ValueInterface::class);

        $processor0
            ->method('process')
            ->willReturn($processor0Value);

        $processor1 = $this->createMock(ProcessorInterface::class);

        $processor1Value = $this->createMock(ValueInterface::class);

        $processor1
            ->method('process')
            ->willReturn($processor1Value);

        $processor = new Pipeline($processor0, $processor1);

        self::assertSame($processor1Value, $processor->process($context, $value));
    }
}
