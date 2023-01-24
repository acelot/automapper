<?php declare(strict_types=1);

namespace Acelot\AutoMapper\Tests\Unit\Extractor;

use Acelot\AutoMapper\Extractor\FromSelf;
use PHPUnit\Framework\TestCase;
use stdClass;

/**
 * @covers \Acelot\AutoMapper\Extractor\FromSelf
 */
final class FromSelfTest extends TestCase
{
    public function testIsExtractable_PassedAnything_AlwaysReturnsTrue(): void
    {
        $extractor = new FromSelf();

        self::assertTrue($extractor->isExtractable(1));
        self::assertTrue($extractor->isExtractable([]));
        self::assertTrue($extractor->isExtractable('string'));
        self::assertTrue($extractor->isExtractable(new stdClass()));
        self::assertTrue($extractor->isExtractable(true));
    }

    public function testExtract_PassedAnything_ReturnsSameValue(): void
    {
        $extractor = new FromSelf();
        $obj = new stdClass();

        self::assertSame(1, $extractor->extract(1));
        self::assertSame([], $extractor->extract([]));
        self::assertSame('string', $extractor->extract('string'));
        self::assertSame($obj, $extractor->extract($obj));
        self::assertTrue($extractor->extract(true));
    }
}
