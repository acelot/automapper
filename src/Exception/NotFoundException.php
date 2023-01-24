<?php declare(strict_types=1);

namespace Acelot\AutoMapper\Exception;

use Acelot\AutoMapper\MapperExceptionInterface;
use RuntimeException;

final class NotFoundException extends RuntimeException implements MapperExceptionInterface
{
    public function __construct(
        private string $path
    )
    {
        parent::__construct(sprintf('Path `%s` not found', $path));
    }

    public function getPath(): string
    {
        return $this->path;
    }
}
