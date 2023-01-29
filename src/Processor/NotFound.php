<?php declare(strict_types=1);

namespace Acelot\AutoMapper\Processor;

use Acelot\AutoMapper\ContextInterface;
use Acelot\AutoMapper\ProcessorInterface;
use Acelot\AutoMapper\Value\NotFoundValue;
use Acelot\AutoMapper\ValueInterface;

final class NotFound implements ProcessorInterface
{
    public function __construct(
        private string $path
    ) {}

    public function process(ContextInterface $context, ValueInterface $value): ValueInterface
    {
        return new NotFoundValue($this->path);
    }
}
