<?php declare(strict_types=1);

namespace Acelot\AutoMapper\Processor;

use Acelot\AutoMapper\ContextInterface;
use Acelot\AutoMapper\ProcessorInterface;
use Acelot\AutoMapper\Value\UserValue;
use Acelot\AutoMapper\ValueInterface;

final class Condition implements ProcessorInterface
{
    /**
     * @var callable<bool>
     */
    private $condition;

    public function __construct(
        callable                   $condition,
        private ProcessorInterface $true,
        private ProcessorInterface $false
    )
    {
        $this->condition = $condition;
    }

    public function getCondition(): callable
    {
        return $this->condition;
    }

    public function getTrueProcessor(): ProcessorInterface
    {
        return $this->true;
    }

    public function getFalseProcessor(): ProcessorInterface
    {
        return $this->false;
    }

    public function process(ContextInterface $context, ValueInterface $value): ValueInterface
    {
        if (($this->condition)($value instanceof UserValue ? $value->getValue() : $value)) {
            return $this->true->process($context, $value);
        }

        return $this->false->process($context, $value);
    }
}
