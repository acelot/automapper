<?php declare(strict_types=1);

namespace Acelot\AutoMapper\Tests\Examples;

use Acelot\AutoMapper\Context\Context;
use PHPUnit\Framework\TestCase;
use function Acelot\AutoMapper\map;
use function Acelot\AutoMapper\pass;
use function Acelot\AutoMapper\toSelf;

final class toSelfTest extends TestCase
{
    public function testExample(): void
    {
        $source = 'original';

        $target = '';

        map(
            new Context(),
            $source,
            $target,
            toSelf(pass())
        );

        self::assertEquals('original', $target);
    }
}
