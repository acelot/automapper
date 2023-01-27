<?php declare(strict_types=1);

namespace Acelot\AutoMapper\Path;

use Acelot\AutoMapper\Exception\UnknownPartException;
use Acelot\AutoMapper\Path\Part\ArrayKey;
use Acelot\AutoMapper\Path\Part\ArrayKeyFirst;
use Acelot\AutoMapper\Path\Part\ArrayKeyLast;
use Acelot\AutoMapper\Path\Part\ObjectMethod;
use Acelot\AutoMapper\Path\Part\ObjectProp;
use Acelot\AutoMapper\Path\Part\SelfPointer;
use InvalidArgumentException;

final class Parser implements ParserInterface
{
    private const GROUP_SELF = 'self';
    private const GROUP_METHOD = 'method';
    private const GROUP_PROP = 'prop';
    private const GROUP_SPROP = 'sprop';
    private const GROUP_POS = 'pos';
    private const GROUP_INDEX = 'index';
    private const GROUP_KEY = 'key';
    private const GROUP_ERROR = 'error';

    private const PATH_PATTERN =
        '/' .
        '(?<' . Parser::GROUP_SELF . '>@)|' . // pointer to self `@`
        '->(?<' . Parser::GROUP_METHOD . '>\w+)\(\)|' . // object method `->methodName()`
        '->(?<' . Parser::GROUP_PROP . '>\w+)|' . // object property `->property`
        '->{(?<' . Parser::GROUP_SPROP . '>[^}]+)}|' . // object property with spaces `->{some weird property}`
        '\[#(?<' . Parser::GROUP_POS . '>first|last)\]|' . // array position `[#first]` or `[#last]`
        '\[(?<' . Parser::GROUP_INDEX . '>\d+)\]|' . // array index `[0]`
        '\[(?<' . Parser::GROUP_KEY . '>[^]]+)\]|' . // array key `[key]` or `[key with spaces]`
        '(?<' . Parser::GROUP_ERROR . '>.+)' . // error indicator
        '/u';

    private const GROUPS = [
        Parser::GROUP_SELF,
        Parser::GROUP_METHOD,
        Parser::GROUP_PROP,
        Parser::GROUP_SPROP,
        Parser::GROUP_POS,
        Parser::GROUP_INDEX,
        Parser::GROUP_KEY,
        Parser::GROUP_ERROR,
    ];

    public function parse(string $path): Path
    {
        if (!preg_match_all(self::PATH_PATTERN, $path, $matches)) {
            throw new InvalidArgumentException('Invalid path');
        }

        if (!empty(array_filter($matches[Parser::GROUP_ERROR]))) {
            throw new InvalidArgumentException('Invalid path');
        }

        $tokens = $this->getTokens($matches);

        return new Path(...$this->convertToParts($tokens));
    }

    private function getTokens(array $matches): array
    {
        $tokens = [];

        for ($i = 0; $i < count($matches[0]); $i++) {
            foreach (self::GROUPS as $group) {
                if (!empty($matches[$group][$i])) {
                    $tokens[$i] = [$group, $matches[$group][$i]];
                    continue 2;
                }
            }
        }

        return $tokens;
    }

    private function convertToParts(array $tokens): array
    {
        $parts = [];

        foreach ($tokens as [$group, $value]) {
            $parts[] = match ($group) {
                self::GROUP_SELF => new SelfPointer(),
                self::GROUP_METHOD => new ObjectMethod($value),
                self::GROUP_PROP, self::GROUP_SPROP => new ObjectProp($value),
                self::GROUP_KEY => new ArrayKey($value),
                self::GROUP_INDEX => new ArrayKey(intval($value)),
                self::GROUP_POS => $value === 'first' ? new ArrayKeyFirst() : new ArrayKeyLast(),
                default => throw new UnknownPartException($group)
            };
        }

        return $parts;
    }
}
