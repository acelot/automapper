<?php declare(strict_types=1);

namespace Acelot\AutoMapper\Tests\Unit\Extractor;

use Acelot\AutoMapper\Extractor\FromArrayKeyLast;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Acelot\AutoMapper\Extractor\FromArrayKeyLast
 */
final class FromArrayKeyLastTest extends TestCase
{
    public function testIsExtractable_PassedNotArray_ReturnsFalse(): void
    {
        $extractor = new FromArrayKeyLast();

        self::assertFalse($extractor->isExtractable(1));
    }

    public function testIsExtractable_PassedEmptyArray_ReturnsFalse(): void
    {
        $extractor = new FromArrayKeyLast();

        self::assertFalse($extractor->isExtractable([]));
    }

    public function testIsExtractable_PassedNotEmptyArray_ReturnsTrue(): void
    {
        $extractor = new FromArrayKeyLast();

        self::assertTrue($extractor->isExtractable([1]));
    }

    public function testExtract_PassedArrayWithSingleElement_ReturnsCorrectValue(): void
    {
        $extractor = new FromArrayKeyLast();

        self::assertSame(1, $extractor->extract([1]));
    }

    public function testExtract_PassedArrayWithMultipleElement_ReturnsCorrectValue(): void
    {
        $extractor = new FromArrayKeyLast();

        self::assertSame(3, $extractor->extract([1, 2, 3]));
    }
}
