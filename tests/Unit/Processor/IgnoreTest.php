<?php declare(strict_types=1);

namespace Acelot\AutoMapper\Tests\Unit\Processor;

use Acelot\AutoMapper\ContextInterface;
use Acelot\AutoMapper\Processor\Ignore;
use Acelot\AutoMapper\Value\IgnoreValue;
use Acelot\AutoMapper\ValueInterface;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Acelot\AutoMapper\Processor\Ignore
 */
final class IgnoreTest extends TestCase
{
    public function testProcess_Constructed_ReturnsInstanceOfIgnoreValue(): void
    {
        $context = $this->createMock(ContextInterface::class);
        $value = $this->createMock(ValueInterface::class);

        $processor = new Ignore();

        self::assertInstanceOf(IgnoreValue::class, $processor->process($context, $value));
    }
}
