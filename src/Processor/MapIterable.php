<?php declare(strict_types=1);

namespace Acelot\AutoMapper\Processor;

use Acelot\AutoMapper\ContextInterface;
use Acelot\AutoMapper\Exception\UnexpectedValueException;
use Acelot\AutoMapper\ProcessorInterface;
use Acelot\AutoMapper\ExceptionValueInterface;
use Acelot\AutoMapper\Value\IgnoreValue;
use Acelot\AutoMapper\Value\UserValue;
use Acelot\AutoMapper\ValueInterface;
use Generator;

final class MapIterable implements ProcessorInterface
{
    public function __construct(
        private ProcessorInterface $processor,
        private bool $keepKeys = false
    ) {}

    public function process(ContextInterface $context, ValueInterface $value): ValueInterface
    {
        if (!$value instanceof UserValue) {
            return $value;
        }

        if (!is_iterable($value->getValue())) {
            throw new UnexpectedValueException('array|Traversable', $value->getValue());
        }

        return new UserValue($this->map($context, $value->getValue()));
    }

    private function map(ContextInterface $context, iterable $iterator): Generator
    {
        foreach ($iterator as $key => $item) {
            $processed = $this->processor->process($context, new UserValue($item));

            if ($processed instanceof IgnoreValue) {
                continue;
            }

            if ($processed instanceof ExceptionValueInterface) {
                throw $processed->getException();
            }

            if ($processed instanceof UserValue) {
                if ($this->keepKeys) {
                    yield $key => $processed->getValue();
                } else {
                    yield $processed->getValue();
                }
            }
        }
    }
}
