<?php declare(strict_types=1);

namespace Acelot\AutoMapper\Tests\Unit\Extractor;

use Acelot\AutoMapper\Extractor\FromObjectMethod;
use Acelot\AutoMapper\Tests\Fixtures\TestClass;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Acelot\AutoMapper\Extractor\FromObjectMethod
 */
final class FromObjectMethodTest extends TestCase
{
    public function testGetMethod_Constructed_ReturnsCorrectValue(): void
    {
        $extractor = new FromObjectMethod('test');

        self::assertSame('test', $extractor->getMethod());
    }

    public function testIsExtractable_PassedNotAnObject_ReturnsFalse(): void
    {
        $extractor = new FromObjectMethod('test');

        self::assertFalse($extractor->isExtractable([]));
    }

    public function testIsExtractable_PassedObjectAndNotExistingMethod_ReturnsFalse(): void
    {
        $extractor = new FromObjectMethod('test');

        self::assertFalse($extractor->isExtractable(new TestClass()));
    }

    public function testIsExtractable_PassedObjectAndExistingMethod_ReturnsTrue(): void
    {
        $extractor = new FromObjectMethod('testMethod');

        self::assertTrue($extractor->isExtractable(new TestClass()));
    }

    public function testExtract_PassedObjectAndExistingMethod_ReturnsCorrectValue(): void
    {
        $extractor = new FromObjectMethod('testMethod');

        self::assertSame('test', $extractor->extract(new TestClass()));
    }
}
