<?php declare(strict_types=1);

namespace Acelot\AutoMapper\Tests\Unit\Path\Part;

use Acelot\AutoMapper\Path\Part\ArrayKey;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Acelot\AutoMapper\Path\Part\ArrayKey
 */
final class ArrayKeyTest extends TestCase
{
    public function testGetKey_ConstructedWithSinglePart_ReturnCorrectValue(): void
    {
        $part = new ArrayKey('key');

        self::assertSame('key', $part->getKey());
    }
}
