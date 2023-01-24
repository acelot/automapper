<?php declare(strict_types=1);

namespace Acelot\AutoMapper\Tests\Unit\Path\Part;

use Acelot\AutoMapper\Path\Part\ObjectProp;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Acelot\AutoMapper\Path\Part\ObjectProp
 */
final class ObjectPropTest extends TestCase
{
    public function testGetKey_ConstructedWithSinglePart_ReturnCorrectValue(): void
    {
        $part = new ObjectProp('prop');

        self::assertSame('prop', $part->getProperty());
    }
}
