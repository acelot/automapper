<?php declare(strict_types=1);

namespace Acelot\AutoMapper\Tests\Unit\Extractor;

use Acelot\AutoMapper\Extractor\FromArrayKeyFirst;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Acelot\AutoMapper\Extractor\FromArrayKeyFirst
 */
final class FromArrayKeyFirstTest extends TestCase
{
    public function testIsExtractable_PassedNotArray_ReturnsFalse(): void
    {
        $extractor = new FromArrayKeyFirst();

        self::assertFalse($extractor->isExtractable(1));
    }

    public function testIsExtractable_PassedEmptyArray_ReturnsFalse(): void
    {
        $extractor = new FromArrayKeyFirst();

        self::assertFalse($extractor->isExtractable([]));
    }

    public function testIsExtractable_PassedNotEmptyArray_ReturnsTrue(): void
    {
        $extractor = new FromArrayKeyFirst();

        self::assertTrue($extractor->isExtractable([1]));
    }

    public function testExtract_PassedArrayWithSingleElement_ReturnsCorrectValue(): void
    {
        $extractor = new FromArrayKeyFirst();

        self::assertSame(1, $extractor->extract([1]));
    }

    public function testExtract_PassedArrayWithMultipleElement_ReturnsCorrectValue(): void
    {
        $extractor = new FromArrayKeyFirst();

        self::assertSame(2, $extractor->extract([2, 1]));
    }
}
