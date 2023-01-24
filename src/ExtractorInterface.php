<?php declare(strict_types=1);

namespace Acelot\AutoMapper;

interface ExtractorInterface
{
    public function isExtractable(mixed $source): bool;

    public function extract(mixed $source): mixed;
}
