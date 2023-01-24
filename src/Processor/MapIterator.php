<?php declare(strict_types=1);

namespace Acelot\AutoMapper\Processor;

use Acelot\AutoMapper\Context\ContextInterface;
use Acelot\AutoMapper\Exception\NotFoundException;
use Acelot\AutoMapper\Exception\UnexpectedValueException;
use Acelot\AutoMapper\ProcessorInterface;
use Acelot\AutoMapper\Value\IgnoreValue;
use Acelot\AutoMapper\Value\NotFoundValue;
use Acelot\AutoMapper\Value\UserValue;
use Acelot\AutoMapper\ValueInterface;
use Generator;
use Iterator;

final class MapIterator implements ProcessorInterface
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

        if (!$value->getValue() instanceof Iterator) {
            throw new UnexpectedValueException(Iterator::class, $value->getValue());
        }

        return new UserValue($this->map($context, $value->getValue()));
    }

    private function map(ContextInterface $context, Iterator $iterator): Generator
    {
        foreach ($iterator as $key => $item) {
            $processed = $this->processor->process($context, new UserValue($item));

            if ($processed instanceof IgnoreValue) {
                continue;
            }

            if ($processed instanceof NotFoundValue) {
                throw new NotFoundException($processed->getPath());
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
