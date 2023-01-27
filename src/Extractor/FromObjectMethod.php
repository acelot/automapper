<?php declare(strict_types=1);

namespace Acelot\AutoMapper\Extractor;

use Acelot\AutoMapper\ExtractorInterface;

final class FromObjectMethod implements ExtractorInterface
{
    public function __construct(
        private string $method
    ) {}

    public function getMethod(): string
    {
        return $this->method;
    }

    public function isExtractable(mixed $source): bool
    {
        return is_object($source) && method_exists($source, $this->method);
    }

    public function extract(mixed $source): mixed
    {
        return call_user_func([$source, $this->method]);
    }
}
