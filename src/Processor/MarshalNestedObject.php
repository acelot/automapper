<?php declare(strict_types=1);

namespace Acelot\AutoMapper\Processor;

use Acelot\AutoMapper\Context\ContextInterface;
use Acelot\AutoMapper\MapperFactoryInterface;
use Acelot\AutoMapper\ObjectFieldInterface;
use Acelot\AutoMapper\ProcessorInterface;
use Acelot\AutoMapper\Value\UserValue;
use Acelot\AutoMapper\ValueInterface;
use stdClass;

final class MarshalNestedObject implements ProcessorInterface
{
    /**
     * @var ObjectFieldInterface[]
     */
    private array $fields;

    public function __construct(
        private MapperFactoryInterface $mapperFactory,
        ObjectFieldInterface           $firstField,
        ObjectFieldInterface           ...$restFields
    )
    {
        $this->fields = [$firstField, ...$restFields];
    }

    public function process(ContextInterface $context, ValueInterface $value): ValueInterface
    {
        if (!$value instanceof UserValue) {
            return $value;
        }

        $target = new stdClass();

        $mapper = $this->mapperFactory->create($context, ...$this->fields);
        $mapper->map($value->getValue(), $target);

        return new UserValue($target);
    }
}
