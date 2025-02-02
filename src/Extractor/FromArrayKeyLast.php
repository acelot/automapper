<?php declare(strict_types=1);

namespace Acelot\AutoMapper\Extractor;

use Acelot\AutoMapper\ExtractorInterface;

/**
 * @implements ExtractorInterface<non-empty-array>
 */
final class FromArrayKeyLast implements ExtractorInterface
{
    public function isExtractable(mixed $source): bool
    {
        return is_array($source) && !empty($source);
    }

    public function extract(mixed $source): mixed
    {
        return $source[array_key_last($source)];
    }
}
