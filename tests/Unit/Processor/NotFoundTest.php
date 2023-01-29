<?php declare(strict_types=1);

namespace Acelot\AutoMapper\Tests\Unit\Processor;

use Acelot\AutoMapper\ContextInterface;
use Acelot\AutoMapper\Processor\NotFound;
use Acelot\AutoMapper\Value\NotFoundValue;
use Acelot\AutoMapper\ValueInterface;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Acelot\AutoMapper\Processor\NotFound
 */
final class NotFoundTest extends TestCase
{
    public function testProcess_Constructed_ReturnsInstanceOfIgnoreValue(): void
    {
        $context = $this->createMock(ContextInterface::class);
        $value = $this->createMock(ValueInterface::class);

        $processor = new NotFound('path');

        self::assertEquals(new NotFoundValue('path'), $processor->process($context, $value));
    }
}
