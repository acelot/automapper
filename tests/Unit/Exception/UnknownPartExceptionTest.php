<?php declare(strict_types=1);

namespace Acelot\AutoMapper\Tests\Unit\Exception;

use Acelot\AutoMapper\Exception\UnknownPartException;
use Acelot\AutoMapper\Tests\Fixtures\TestPart;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Acelot\AutoMapper\Exception\UnknownPartException
 */
final class UnknownPartExceptionTest extends TestCase
{
    public function testGetMessage_Constructed_ReturnsCorrectMessage(): void
    {
        $exception = new UnknownPartException(new TestPart());

        self::assertSame('Unknown part `Acelot\AutoMapper\Tests\Fixtures\TestPart`', $exception->getMessage());
    }

    public function testGetPart_Constructed_ReturnsCorrectValue(): void
    {
        $part = new TestPart();
        $exception = new UnknownPartException($part);

        self::assertSame($part, $exception->getPart());
    }
}
