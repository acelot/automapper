<?php declare(strict_types=1);

namespace Acelot\AutoMapper\Processor;

use Acelot\AutoMapper\ContextInterface;
use Acelot\AutoMapper\ProcessorInterface;
use Acelot\AutoMapper\Value\UserValue;
use Acelot\AutoMapper\ValueInterface;

final class CallWithContext implements ProcessorInterface
{
    /**
     * @param callable $callable
     */
    private $callable;

    public function __construct(callable $callable)
    {
        $this->callable = $callable;
    }

    public function process(ContextInterface $context, ValueInterface $value): ValueInterface
    {
        if (!$value instanceof UserValue) {
            return $value;
        }

        /** @var mixed $newValue */
        $newValue = ($this->callable)($context, $value->getValue());

        if (!$newValue instanceof ValueInterface) {
            $newValue = new UserValue($newValue);
        }

        return $newValue;
    }
}
