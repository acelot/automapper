<?php declare(strict_types=1);

namespace Acelot\AutoMapper\Tests\Unit\Processor;

use Acelot\AutoMapper\ContextInterface;
use Acelot\AutoMapper\Processor\GetFromContext;
use Acelot\AutoMapper\Value\NotFoundValue;
use Acelot\AutoMapper\Value\UserValue;
use Acelot\AutoMapper\ValueInterface;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Acelot\AutoMapper\Processor\GetFromContext
 */
final class GetFromContextTest extends TestCase
{
    public function testProcess_ContextPassed_CallsContextHasMethod(): void
    {
        $context = $this->createMock(ContextInterface::class);
        $value = $this->createMock(ValueInterface::class);

        $processor = new GetFromContext('key');

        $context
            ->expects(self::once())
            ->method('has')
            ->with(self::equalTo('key'));

        $processor->process($context, $value);
    }

    public function testProcess_ContextHasNoKey_ReturnsNotFoundValue(): void
    {
        $context = $this->createMock(ContextInterface::class);
        $context
            ->method('has')
            ->willReturn(false);

        $value = $this->createMock(ValueInterface::class);

        $processor = new GetFromContext('key');

        self::assertEquals(new NotFoundValue('key'), $processor->process($context, $value));
    }

    public function testProcess_ContextHasKey_CallsContextGetMethod(): void
    {
        $context = $this->createMock(ContextInterface::class);
        $context
            ->method('has')
            ->willReturn(true);

        $value = $this->createMock(ValueInterface::class);

        $processor = new GetFromContext('key');

        $context
            ->expects(self::once())
            ->method('get')
            ->with(self::equalTo('key'));

        $processor->process($context, $value);
    }

    public function testProcess_ContextHasKey_ReturnsValueFromContext(): void
    {
        $context = $this->createMock(ContextInterface::class);
        $context
            ->method('has')
            ->willReturn(true);

        $context
            ->method('get')
            ->willReturn('test');

        $value = $this->createMock(ValueInterface::class);

        $processor = new GetFromContext('key');

        self::assertEquals(new UserValue('test'), $processor->process($context, $value));
    }
}
