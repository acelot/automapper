<?php declare(strict_types=1);

namespace Acelot\AutoMapper\Processor;

use Acelot\AutoMapper\ContextInterface;
use Acelot\AutoMapper\Exception\UnexpectedValueException;
use Acelot\AutoMapper\ProcessorInterface;
use Acelot\AutoMapper\Value\NotFoundValue;
use Acelot\AutoMapper\Value\UserValue;
use Acelot\AutoMapper\ValueInterface;

final class FindWithContext implements ProcessorInterface
{
    /**
     * @var callable(ContextInterface, mixed, int|string): bool
     */
    private $predicate;

    /**
     * @param callable(ContextInterface, mixed, int|string): bool $predicate
     */
    public function __construct(callable $predicate)
    {
        $this->predicate = $predicate;
    }

    public function process(ContextInterface $context, ValueInterface $value): ValueInterface
    {
        if (!$value instanceof UserValue) {
            return $value;
        }

        $innerValue = $value->getValue();

        if (!is_iterable($innerValue)) {
            throw new UnexpectedValueException('array|Traversable', $innerValue);
        }

        /**
         * @var array-key $key
         * @var mixed $item
         */
        foreach ($innerValue as $key => $item) {
            if (($this->predicate)($context, $item, $key)) {
                return new UserValue($item);
            }
        }

        return new NotFoundValue('by predicate');
    }
}
