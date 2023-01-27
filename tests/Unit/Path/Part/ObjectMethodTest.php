<?php declare(strict_types=1);

namespace Acelot\AutoMapper\Tests\Unit\Path\Part;

use Acelot\AutoMapper\Path\Part\ObjectMethod;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Acelot\AutoMapper\Path\Part\ObjectMethod
 */
final class ObjectMethodTest extends TestCase
{
    public function testGetKey_ConstructedWithSinglePart_ReturnCorrectValue(): void
    {
        $part = new ObjectMethod('method');

        self::assertSame('method', $part->getMethod());
    }
}
