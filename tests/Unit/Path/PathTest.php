<?php declare(strict_types=1);

namespace Acelot\AutoMapper\Tests\Unit\Path;

use Acelot\AutoMapper\Path\PartInterface;
use Acelot\AutoMapper\Path\Path;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Acelot\AutoMapper\Path\Path
 */
final class PathTest extends TestCase
{
    public function testGetParts_ConstructedWithSinglePart_ReturnCorrectValue(): void
    {
        $part = $this->createMock(PartInterface::class);
        $path = new Path($part);

        self::assertSame([$part], $path->getParts());
    }

    public function testGetParts_ConstructedWithMultipleParts_ReturnCorrectValue(): void
    {
        $part0 = $this->createMock(PartInterface::class);
        $part1 = $this->createMock(PartInterface::class);
        $path = new Path($part0, $part1);

        self::assertSame([$part0, $part1], $path->getParts());
    }
}
