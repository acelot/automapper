<?php declare(strict_types=1);

namespace Acelot\AutoMapper;

use Acelot\AutoMapper\Context\ContextInterface;
use Acelot\AutoMapper\Exception\NotFoundException;
use Acelot\AutoMapper\Value\IgnoreValue;
use Acelot\AutoMapper\Value\NotFoundValue;
use Acelot\AutoMapper\Value\UserValue;

final class Mapper implements MapperInterface
{
    private ContextInterface $context;

    /**
     * @var FieldInterface[]
     */
    private array $fields;

    public function __construct(ContextInterface $context, FieldInterface ...$fields)
    {
        $this->context = $context;
        $this->fields = $fields;
    }

    public function getContext(): ContextInterface
    {
        return $this->context;
    }

    /**
     * @return FieldInterface[]
     */
    public function getFields(): array
    {
        return $this->fields;
    }

    public function map(mixed $source, mixed &$target): void
    {
        foreach ($this->fields as $field) {
            $value = $field->getProcessor()->process($this->context, new UserValue($source));

            if ($value instanceof IgnoreValue) {
                continue;
            }

            if ($value instanceof NotFoundValue) {
                throw new NotFoundException($value->getPath());
            }

            if ($value instanceof UserValue) {
                $field->writeValue($target, $value->getValue());
            }
        }
    }
}
