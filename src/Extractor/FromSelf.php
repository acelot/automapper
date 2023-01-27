<?php declare(strict_types=1);

namespace Acelot\AutoMapper\Extractor;

use Acelot\AutoMapper\ExtractorInterface;

final class FromSelf implements ExtractorInterface
{
    public function isExtractable(mixed $source): bool
    {
        return true;
    }

    public function extract(mixed $source): mixed
    {
        return $source;
    }
}
