<?php declare(strict_types=1);

namespace Acelot\AutoMapper\Processor;

use Acelot\AutoMapper\Context\ContextInterface;
use Acelot\AutoMapper\ProcessorInterface;
use Acelot\AutoMapper\ValueInterface;

final class Pipeline implements ProcessorInterface
{
    /**
     * @param ProcessorInterface[] $processors
     */
    private array $processors;

    public function __construct(ProcessorInterface ...$processors)
    {
        $this->processors = $processors;
    }

    public function process(ContextInterface $context, ValueInterface $value): ValueInterface
    {
        foreach ($this->processors as $processor) {
            $value = $processor->process($context, $value);
        }

        return $value;
    }
}
