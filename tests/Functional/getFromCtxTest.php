<?php declare(strict_types=1);

namespace Acelot\AutoMapper\Tests\Functional;

use Acelot\AutoMapper\AutoMapper as a;
use Acelot\AutoMapper\Context\Context;
use PHPUnit\Framework\TestCase;

final class getFromCtxTest extends TestCase
{
    public function testExample(): void
    {
        $source = [];

        $result = a::marshalArray(
            new Context([
                'host' => 'localhost',
                'port' => 3306,
            ]),
            $source,
            a::toKey('db_host', a::getFromCtx('host')),
            a::toKey('db_port', a::getFromCtx('port')),
        );

        self::assertSame(
            [
                'db_host' => 'localhost',
                'db_port' => 3306,
            ],
            $result
        );
    }
}
