<?php declare(strict_types=1);

namespace Acelot\AutoMapper\Tests\Unit\Context;

use Acelot\AutoMapper\Context\Context;
use OutOfBoundsException;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Acelot\AutoMapper\Context\Context
 */
final class ContextTest extends TestCase
{
    public function testHas_KeyDoesntExists_ReturnsFalse(): void
    {
        $context = new Context([]);

        self::assertFalse($context->has('test'));
    }

    public function testHas_KeyExists_ReturnsTrue(): void
    {
        $context = new Context(['test' => true]);

        self::assertTrue($context->has('test'));
    }

    public function testGet_KeyDoesntExists_ThrowsOutOfBoundsException(): void
    {
        $context = new Context(['test' => true]);

        self::expectException(OutOfBoundsException::class);

        $context->get('some_key');
    }

    public function testGet_KeyExists_ReturnsCorrectValue(): void
    {
        $context = new Context(['test' => 'value']);

        self::assertSame('value', $context->get('test'));
    }

    public function testSet_PassedKeyAndValue_StoresValue(): void
    {
        $context = new Context([]);
        $context->set('key', 'value');

        self::assertSame('value', $context->get('key'));
    }
}
