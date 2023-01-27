<?php declare(strict_types=1);

namespace Acelot\AutoMapper\Tests\Functional;

use Acelot\AutoMapper\Context\Context;
use PHPUnit\Framework\TestCase;
use function Acelot\AutoMapper\getFromCtx;
use function Acelot\AutoMapper\marshalArray;
use function Acelot\AutoMapper\toKey;

final class getFromCtxTest extends TestCase
{
    public function testExample(): void
    {
        $source = [];

        $result = marshalArray(
            new Context([
                'host' => 'localhost',
                'port' => 3306,
            ]),
            $source,
            toKey('db_host', getFromCtx('host')),
            toKey('db_port', getFromCtx('port')),
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
