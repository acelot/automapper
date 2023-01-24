<?php declare(strict_types=1);

namespace Acelot\AutoMapper\Extractor;

use Acelot\AutoMapper\ExtractorInterface;

final class FromArrayKey implements ExtractorInterface
{
    public function __construct(
        private string $key
    ) {}

    public function getKey(): string
    {
        return $this->key;
    }

    public function isExtractable(mixed $source): bool
    {
        return is_array($source) && array_key_exists($this->key, $source);
    }

    public function extract(mixed $source): mixed
    {
        return $source[$this->key];
    }
}
