<?php declare(strict_types=1);

namespace Acelot\AutoMapper\Tests\Functional;

use Acelot\AutoMapper\AutoMapper as a;
use Acelot\AutoMapper\Context\Context;
use PHPUnit\Framework\TestCase;

final class toSelfTest extends TestCase
{
    public function testExample(): void
    {
        $source = 'original';

        $target = '';

        a::map(
            new Context(),
            $source,
            $target,
            a::toSelf(a::pass())
        );

        self::assertEquals('original', $target);
    }
}
