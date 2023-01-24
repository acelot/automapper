<?php declare(strict_types=1);

namespace Acelot\AutoMapper\Tests\Unit\Extractor;

use Acelot\AutoMapper\Extractor\FromObjectProp;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Acelot\AutoMapper\Extractor\FromObjectProp
 */
final class FromObjectPropTest extends TestCase
{
    public function testGetProperty_Constructed_ReturnsCorrectValue(): void
    {
        $extractor = new FromObjectProp('test');

        self::assertSame('test', $extractor->getProperty());
    }

    public function testIsExtractable_PassedNotAnObject_ReturnsFalse(): void
    {
        $extractor = new FromObjectProp('test');

        self::assertFalse($extractor->isExtractable([]));
    }

    public function testIsExtractable_PassedObjectAndNotExistingProperty_ReturnsFalse(): void
    {
        $extractor = new FromObjectProp('test');

        self::assertFalse($extractor->isExtractable((object)['test0' => 1]));
    }

    public function testIsExtractable_PassedObjectAndExistingProperty_ReturnsTrue(): void
    {
        $extractor = new FromObjectProp('test');

        self::assertTrue($extractor->isExtractable((object)['test' => 1]));
    }

    public function testExtract_PassedObjectAndExistingProperty_ReturnsCorrectValue(): void
    {
        $extractor = new FromObjectProp('test');

        self::assertSame(123, $extractor->extract((object)['test' => 123]));
    }
}
