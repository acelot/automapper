<?php declare(strict_types=1);

namespace Acelot\AutoMapper\Tests\Unit\Value;

use Acelot\AutoMapper\Value\NotFoundValue;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Acelot\AutoMapper\Value\NotFoundValue
 */
final class NotFoundValueTest extends TestCase
{
    public function testGetPath_Constructed_ReturnCorrectValue(): void
    {
        $value = new NotFoundValue('test');

        self::assertSame('test', $value->getPath());
    }
}
