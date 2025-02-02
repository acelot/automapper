<?php declare(strict_types=1);

namespace Acelot\AutoMapper\Exception;

use Acelot\AutoMapper\MapperExceptionInterface;
use Acelot\AutoMapper\Path\PartInterface;
use LogicException;

final class UnknownPartException extends LogicException implements MapperExceptionInterface
{
    public function __construct(
        private PartInterface|string $part
    )
    {
        parent::__construct(sprintf('Unknown part `%s`', is_string($part) ? $part : $part::class));
    }

    public function getPart(): PartInterface|string
    {
        return $this->part;
    }
}
