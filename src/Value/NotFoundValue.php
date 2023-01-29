<?php declare(strict_types=1);

namespace Acelot\AutoMapper\Value;

use Acelot\AutoMapper\Exception\NotFoundException;
use Throwable;

final class NotFoundValue implements ExceptionValueInterface
{
    public function __construct(
        private string $path
    ) {}

    public function getPath(): string
    {
        return $this->path;
    }

    public function getException(): Throwable
    {
        return new NotFoundException($this->path);
    }
}
