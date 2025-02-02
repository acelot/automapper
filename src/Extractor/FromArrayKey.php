<?php declare(strict_types=1);

namespace Acelot\AutoMapper\Extractor;

use Acelot\AutoMapper\ExtractorInterface;

/**
 * @implements ExtractorInterface<array|string>
 */
final class FromArrayKey implements ExtractorInterface
{
    /**
     * @param array-key $key
     */
    public function __construct(
        private mixed $key
    ) {}

    /**
     * @return array-key
     */
    public function getKey(): mixed
    {
        return $this->key;
    }

    public function isExtractable(mixed $source): bool
    {
        return (
            (is_string($source) && is_int($this->key) && isset($source[$this->key])) ||
            (is_array($source) && array_key_exists($this->key, $source))
        );
    }

    public function extract(mixed $source): mixed
    {
        return is_string($source) ? $source[(int) $this->key] : $source[$this->key];
    }
}
