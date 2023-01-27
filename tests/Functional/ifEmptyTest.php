<?php declare(strict_types=1);

namespace Acelot\AutoMapper\Tests\Functional;

use Acelot\AutoMapper\Context\Context;
use PHPUnit\Framework\TestCase;
use function Acelot\AutoMapper\get;
use function Acelot\AutoMapper\ifEmpty;
use function Acelot\AutoMapper\ignore;
use function Acelot\AutoMapper\marshalArray;
use function Acelot\AutoMapper\pass;
use function Acelot\AutoMapper\pipe;
use function Acelot\AutoMapper\toKey;
use function Acelot\AutoMapper\value;

final class ifEmptyTest extends TestCase
{
    public function testExample(): void
    {
        $source = [
            'id' => 0,
            'title' => null,
            'tags' => [],
            'desc' => '',
        ];

        $result = marshalArray(
            new Context(),
            $source,
            toKey('id', pipe(
                get('[id]'),
                ifEmpty(ignore()),
            )),
            toKey('title', pipe(
                get('[title]'),
                ifEmpty(value('default title')),
            )),
            toKey('tags', pipe(
                get('[tags]'),
                ifEmpty(pass()),
            )),
            toKey('desc', pipe(
                get('[desc]'),
                ifEmpty(ignore()),
            )),
        );

        self::assertSame(
            [
                'title' => 'default title',
                'tags' => [],
            ],
            $result
        );
    }
}
