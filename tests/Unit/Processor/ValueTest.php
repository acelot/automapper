<?php declare(strict_types=1);

namespace Acelot\AutoMapper\Tests\Unit\Processor;

use Acelot\AutoMapper\Context\ContextInterface;
use Acelot\AutoMapper\Processor\Value;
use Acelot\AutoMapper\Value\UserValue;
use Acelot\AutoMapper\ValueInterface;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Acelot\AutoMapper\Processor\Value
 */
final class ValueTest extends TestCase
{
    public function testProcess_PassedNotValueInterfaceValue_ReturnsSameValue(): void
    {
        $context = $this->createMock(ContextInterface::class);
        $value = $this->createMock(ValueInterface::class);

        $processor = new Value('test');

        self::assertEquals(new UserValue('test'), $processor->process($context, $value));
    }

    public function testProcess_PassedInstanceOfValueInterface_ReturnsSameValue(): void
    {
        $context = $this->createMock(ContextInterface::class);
        $value = $this->createMock(ValueInterface::class);
        $passedValue = $this->createMock(ValueInterface::class);

        $processor = new Value($passedValue);

        self::assertSame($passedValue, $processor->process($context, $value));
    }
}
