<?php declare(strict_types=1);

namespace Acelot\AutoMapper\Tests\Unit\Processor;

use Acelot\AutoMapper\Context\ContextInterface;
use Acelot\AutoMapper\Processor\Condition;
use Acelot\AutoMapper\ProcessorInterface;
use Acelot\AutoMapper\Tests\Fixtures\TestGetInterface;
use Acelot\AutoMapper\ValueInterface;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Acelot\AutoMapper\Processor\Condition
 */
final class ConditionTest extends TestCase
{
    public function testProcess_PassedArgs_CallsConditionCallable(): void
    {
        $context = $this->createMock(ContextInterface::class);
        $value = $this->createMock(ValueInterface::class);
        $someClass = $this->createMock(TestGetInterface::class);

        $true = $this->createMock(ProcessorInterface::class);
        $false = $this->createMock(ProcessorInterface::class);

        $processor = new Condition([$someClass, 'get'], $true, $false);

        $someClass
            ->expects(self::once())
            ->method('get')
            ->with(self::equalTo($value));

        $processor->process($context, $value);
    }

    public function testProcess_ConditionCallableReturnsTrue_CallsTrueProcessor(): void
    {
        $context = $this->createMock(ContextInterface::class);
        $value = $this->createMock(ValueInterface::class);

        $someClass = $this->createMock(TestGetInterface::class);
        $someClass
            ->method('get')
            ->willReturn(true);

        $true = $this->createMock(ProcessorInterface::class);
        $false = $this->createMock(ProcessorInterface::class);

        $processor = new Condition([$someClass, 'get'], $true, $false);

        $true
            ->expects(self::once())
            ->method('process')
            ->with(self::equalTo($context), self::equalTo($value));

        $processor->process($context, $value);
    }

    public function testProcess_ConditionCallableReturnsFalse_CallsFalseProcessor(): void
    {
        $context = $this->createMock(ContextInterface::class);
        $value = $this->createMock(ValueInterface::class);

        $someClass = $this->createMock(TestGetInterface::class);
        $someClass
            ->method('get')
            ->willReturn(false);

        $true = $this->createMock(ProcessorInterface::class);
        $false = $this->createMock(ProcessorInterface::class);

        $processor = new Condition([$someClass, 'get'], $true, $false);

        $false
            ->expects(self::once())
            ->method('process')
            ->with(self::equalTo($context), self::equalTo($value));

        $processor->process($context, $value);
    }
}
