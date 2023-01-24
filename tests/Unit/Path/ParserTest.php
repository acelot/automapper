<?php declare(strict_types=1);

namespace Acelot\AutoMapper\Tests\Unit\Path;

use Acelot\AutoMapper\Path\Parser;
use Acelot\AutoMapper\Path\Part\ArrayKey;
use Acelot\AutoMapper\Path\Part\ArrayKeyFirst;
use Acelot\AutoMapper\Path\Part\ArrayKeyLast;
use Acelot\AutoMapper\Path\Part\ObjectMethod;
use Acelot\AutoMapper\Path\Part\ObjectProp;
use Acelot\AutoMapper\Path\Part\SelfPointer;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

final class ParserTest extends TestCase
{
    /**
     * @param string $path
     * @param array $expectedParts
     * @return void
     *
     * @dataProvider pathsProvider
     */
    public function testParse_PassedPath_ExpectsCorrectPathParts(string $path, array $expectedParts): void
    {
        $parser = new Parser();
        $path = $parser->parse($path);

        $parts = [];

        foreach ($path->getParts() as $part) {
            if ($part instanceof ArrayKey) {
                $parts[] = ['array_key', $part->getKey()];
            } else if ($part instanceof ArrayKeyFirst) {
                $parts[] = ['array_key_first'];
            } else if ($part instanceof ArrayKeyLast) {
                $parts[] = ['array_key_last'];
            } else if ($part instanceof ObjectMethod) {
                $parts[] = ['object_method', $part->getMethod()];
            } else if ($part instanceof ObjectProp) {
                $parts[] = ['object_prop', $part->getProperty()];
            } else if ($part instanceof SelfPointer) {
                $parts[] = ['self'];
            }
        }

        self::assertSame($expectedParts, $parts);
    }

    public function pathsProvider(): array
    {
        return [
            [
                '@',
                [
                    ['self'],
                ],
            ],
            [
                '[key]',
                [
                    ['array_key', 'key'],
                ],
            ],
            [
                '[key with spaces]',
                [
                    ['array_key', 'key with spaces'],
                ],
            ],
            [
                '[#first]',
                [
                    ['array_key_first'],
                ],
            ],
            [
                '[#last]',
                [
                    ['array_key_last'],
                ],
            ],
            [
                '->prop',
                [
                    ['object_prop', 'prop'],
                ],
            ],
            [
                '->{prop with spaces}',
                [
                    ['object_prop', 'prop with spaces'],
                ],
            ],
            [
                '->method()',
                [
                    ['object_method', 'method'],
                ],
            ],
            [
                '@[key][key with spaces][#first][#last]->prop->{prop with spaces}->method()',
                [
                    ['self'],
                    ['array_key', 'key'],
                    ['array_key', 'key with spaces'],
                    ['array_key_first'],
                    ['array_key_last'],
                    ['object_prop', 'prop'],
                    ['object_prop', 'prop with spaces'],
                    ['object_method', 'method'],
                ],
            ],
        ];
    }

    public function testParse_PassedEmptyPath_ThrowsInvalidArgumentException(): void
    {
        $parser = new Parser();

        self::expectException(InvalidArgumentException::class);

        $parser->parse('');
    }

    public function testParse_PassedInvalidPath_ThrowsInvalidArgumentException(): void
    {
        $parser = new Parser();

        self::expectException(InvalidArgumentException::class);

        $parser->parse('a');
    }
}
