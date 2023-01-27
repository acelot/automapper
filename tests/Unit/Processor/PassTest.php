<?php declare(strict_types=1);

namespace Acelot\AutoMapper\Tests\Unit\Processor;

use Acelot\AutoMapper\Context\ContextInterface;
use Acelot\AutoMapper\Processor\Pass;
use Acelot\AutoMapper\ValueInterface;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Acelot\AutoMapper\Processor\Pass
 */
final class PassTest extends TestCase
{
    public function testProcess_Constructed_ReturnsSameValue(): void
    {
        $context = $this->createMock(ContextInterface::class);
        $value = $this->createMock(ValueInterface::class);

        $processor = new Pass();

        self::assertSame($value, $processor->process($context, $value));
    }
}
