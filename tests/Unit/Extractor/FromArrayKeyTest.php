<?php declare(strict_types=1);

namespace Acelot\AutoMapper\Tests\Unit\Extractor;

use Acelot\AutoMapper\Extractor\FromArrayKey;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Acelot\AutoMapper\Extractor\FromArrayKey
 */
final class FromArrayKeyTest extends TestCase
{
    public function testGetKey_Constructed_ReturnsCorrectValue(): void
    {
        $extractor = new FromArrayKey('test');

        self::assertSame('test', $extractor->getKey());
    }

    public function testIsExtractable_PassedNotArray_ReturnsFalse(): void
    {
        $extractor = new FromArrayKey('test');

        self::assertFalse($extractor->isExtractable(1));
    }

    public function testIsExtractable_PassedArrayAndNotExistingKey_ReturnsFalse(): void
    {
        $extractor = new FromArrayKey('test');

        self::assertFalse($extractor->isExtractable(['test0' => 1]));
    }

    public function testIsExtractable_PassedArrayAndExistingKey_ReturnsTrue(): void
    {
        $extractor = new FromArrayKey('test');

        self::assertTrue($extractor->isExtractable(['test' => 1]));
    }

    public function testExtract_PassedArrayAndExistingKey_ReturnsCorrectValue(): void
    {
        $extractor = new FromArrayKey('test');

        self::assertSame(123, $extractor->extract(['test' => 123]));
    }
}
