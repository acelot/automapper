<?php declare(strict_types=1);

namespace Acelot\AutoMapper\Tests\Unit\Processor;

use Acelot\AutoMapper\Context\ContextInterface;
use Acelot\AutoMapper\Processor\Call;
use Acelot\AutoMapper\Tests\Fixtures\TestGetInterface;
use Acelot\AutoMapper\Value\UserValue;
use Acelot\AutoMapper\ValueInterface;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Acelot\AutoMapper\Processor\Call
 */
final class CallTest extends TestCase
{
    public function testProcess_PassedNotUserValue_NeverCallsCallable(): void
    {
        $context = $this->createMock(ContextInterface::class);
        $value = $this->createMock(ValueInterface::class);
        $someClass = $this->createMock(TestGetInterface::class);

        $processor = new Call([$someClass, 'get']);

        $someClass
            ->expects(self::never())
            ->method('get');

        $processor->process($context, $value);
    }

    public function testProcess_PassedUserValue_CallsCallable(): void
    {
        $context = $this->createMock(ContextInterface::class);
        $value = new UserValue('test');
        $someClass = $this->createMock(TestGetInterface::class);

        $processor = new Call([$someClass, 'get']);

        $someClass
            ->expects(self::once())
            ->method('get')
            ->with(self::equalTo('test'));

        $processor->process($context, $value);
    }

    public function testProcess_PassedUserValue_ReturnsUserValueFromCallable(): void
    {
        $context = $this->createMock(ContextInterface::class);
        $value = new UserValue('test');

        $someClass = $this->createMock(TestGetInterface::class);
        $someClass
            ->method('get')
            ->willReturn('new_value');

        $processor = new Call([$someClass, 'get']);

        self::assertEquals(new UserValue('new_value'), $processor->process($context, $value));
    }
}
