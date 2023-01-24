<?php declare(strict_types=1);

namespace Acelot\AutoMapper\Processor;

use Acelot\AutoMapper\Context\ContextInterface;
use Acelot\AutoMapper\ProcessorInterface;
use Acelot\AutoMapper\Value\UserValue;
use Acelot\AutoMapper\ValueInterface;

final class ConditionWithContext implements ProcessorInterface
{
    /**
     * @var callable<bool>
     */
    private $condition;

    public function __construct(
        callable $condition,
        private ProcessorInterface $ifTrue,
        private ProcessorInterface $ifFalse
    )
    {
        $this->condition = $condition;
    }

    public function process(ContextInterface $context, ValueInterface $value): ValueInterface
    {
        $result = ($this->condition)(
            $context,
            $value instanceof UserValue ? $value->getValue() : $value
        );

        if ($result) {
            return $this->ifTrue->process($context, $value);
        }

        return $this->ifFalse->process($context, $value);
    }
}
