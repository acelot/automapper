<?php declare(strict_types=1);

namespace Acelot\AutoMapper\Tests\Unit\Value;

use Acelot\AutoMapper\Value\UserValue;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Acelot\AutoMapper\Value\UserValue
 */
final class UserValueTest extends TestCase
{
    public function testGetValue_Constructed_ReturnCorrectValue(): void
    {
        $value = new UserValue('test');

        self::assertSame('test', $value->getValue());
    }
}
