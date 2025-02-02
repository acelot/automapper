<?php declare(strict_types=1);

namespace Acelot\AutoMapper;

/**
 * @template T
 */
interface ExtractorInterface
{
    public function isExtractable(mixed $source): bool;

    /**
     * @param T $source
     * @return mixed
     */
    public function extract(mixed $source): mixed;
}
