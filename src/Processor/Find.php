<?php declare(strict_types=1);

namespace Acelot\AutoMapper\Processor;

use Acelot\AutoMapper\Context\ContextInterface;
use Acelot\AutoMapper\Exception\UnexpectedValueException;
use Acelot\AutoMapper\ProcessorInterface;
use Acelot\AutoMapper\Value\NotFoundValue;
use Acelot\AutoMapper\Value\UserValue;
use Acelot\AutoMapper\ValueInterface;

final class Find implements ProcessorInterface
{
    /**
     * @var callable<bool>
     */
    private $predicate;

    /**
     * @param callable<bool> $predicate
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

        if (!is_iterable($value->getValue())) {
            throw new UnexpectedValueException('array|Traversable', $value->getValue());
        }

        foreach ($value->getValue() as $key => $item) {
            if (($this->predicate)($item, $key)) {
                return new UserValue($item);
            }
        }

        return new NotFoundValue('by predicate');
    }
}
