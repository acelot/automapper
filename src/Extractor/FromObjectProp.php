<?php declare(strict_types=1);

namespace Acelot\AutoMapper\Extractor;

use Acelot\AutoMapper\ExtractorInterface;

final class FromObjectProp implements ExtractorInterface
{
    public function __construct(
        private string $property
    ) {}

    public function getProperty(): string
    {
        return $this->property;
    }

    public function isExtractable(mixed $source): bool
    {
        return is_object($source) && property_exists($source, $this->property);
    }

    public function extract(mixed $source): mixed
    {
        return $source->{$this->property};
    }
}
