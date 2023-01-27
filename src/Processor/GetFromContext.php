<?php declare(strict_types=1);

namespace Acelot\AutoMapper\Processor;

use Acelot\AutoMapper\Context\ContextInterface;
use Acelot\AutoMapper\ProcessorInterface;
use Acelot\AutoMapper\Value\NotFoundValue;
use Acelot\AutoMapper\Value\UserValue;
use Acelot\AutoMapper\ValueInterface;

final class GetFromContext implements ProcessorInterface
{
    public function __construct(
        private string $key
    ) {}

    public function process(ContextInterface $context, ValueInterface $value): ValueInterface
    {
        if (!$context->has($this->key)) {
            return new NotFoundValue($this->key);
        }

        return new UserValue($context->get($this->key));
    }
}
