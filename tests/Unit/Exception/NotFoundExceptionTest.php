<?php declare(strict_types=1);

namespace Acelot\AutoMapper\Tests\Unit\Exception;

use Acelot\AutoMapper\Exception\NotFoundException;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Acelot\AutoMapper\Exception\NotFoundException
 */
final class NotFoundExceptionTest extends TestCase
{
    public function testGetMessage_Constructed_ReturnsCorrectMessage(): void
    {
        $exception = new NotFoundException('test_path');

        self::assertSame('Path `test_path` not found', $exception->getMessage());
    }

    public function testGetPath_Constructed_ReturnsCorrectValue(): void
    {
        $exception = new NotFoundException('test_path');

        self::assertSame('test_path', $exception->getPath());
    }
}
