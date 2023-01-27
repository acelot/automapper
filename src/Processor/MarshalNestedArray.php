<?php declare(strict_types=1);

namespace Acelot\AutoMapper\Processor;

use Acelot\AutoMapper\Context\ContextInterface;
use Acelot\AutoMapper\Field\ToArrayKey;
use Acelot\AutoMapper\MapperFactoryInterface;
use Acelot\AutoMapper\ProcessorInterface;
use Acelot\AutoMapper\Value\UserValue;
use Acelot\AutoMapper\ValueInterface;

final class MarshalNestedArray implements ProcessorInterface
{
    /**
     * @var ToArrayKey[]
     */
    private array $fields;

    public function __construct(
        private MapperFactoryInterface $mapperFactory,
        ToArrayKey                     $firstField,
        ToArrayKey                     ...$restFields
    )
    {
        $this->fields = [$firstField, ...$restFields];
    }

    public function process(ContextInterface $context, ValueInterface $value): ValueInterface
    {
        if (!$value instanceof UserValue) {
            return $value;
        }

        $target = [];

        $mapper = $this->mapperFactory->create($context, ...$this->fields);
        $mapper->map($value->getValue(), $target);

        return new UserValue($target);
    }
}
