<?php declare(strict_types=1);

namespace Acelot\AutoMapper\Processor;

use Acelot\AutoMapper\ContextInterface;
use Acelot\AutoMapper\Exception\UnexpectedValueException;
use Acelot\AutoMapper\ProcessorInterface;
use Acelot\AutoMapper\Value\UserValue;
use Acelot\AutoMapper\ValueInterface;
use InvalidArgumentException;

final class AssertType implements ProcessorInterface
{
    public const BOOL = 'bool';
    public const INT = 'int';
    public const FLOAT = 'float';
    public const STRING = 'string';
    public const TO_STRING = '__toString';
    public const ITERABLE = 'iterable';
    public const ARRAY = 'array';
    public const OBJECT = 'object';
    public const SCALAR = 'scalar';
    public const CALLABLE = 'callable';
    public const NUMERIC = 'numeric';
    public const COUNTABLE = 'countable';
    public const RESOURCE = 'resource';
    public const NULL = 'null';

    /**
     * @var string[]
     */
    private array $oneOfTypes;

    public function __construct(string $oneOfType, string ...$oneOfTypes)
    {
        $this->oneOfTypes = [$oneOfType, ...$oneOfTypes];
    }

    /**
     * @return string[]
     */
    public function getOneOfTypes(): array
    {
        return $this->oneOfTypes;
    }

    public function process(ContextInterface $context, ValueInterface $value): ValueInterface
    {
        if (!$value instanceof UserValue) {
            return $value;
        }

        /** @var callable[] $asserts */
        $asserts = array_map(fn($type) => $this->getAssert($type), $this->oneOfTypes);

        foreach ($asserts as $assert) {
            if ($assert($value->getValue())) {
                return $value;
            }
        }

        throw new UnexpectedValueException(join('|', $this->oneOfTypes), $value->getValue());
    }

    private function getAssert(string $type): callable
    {
        return match ($type) {
            self::BOOL => static fn($v) => is_bool($v),
            self::INT => static fn($v) => is_int($v),
            self::FLOAT => static fn($v) => is_float($v),
            self::SCALAR => static fn($v) => is_scalar($v),
            self::STRING => static fn($v) => is_string($v),
            self::TO_STRING => static fn($v) => is_object($v) && method_exists($v, '__toString'),
            self::ITERABLE => static fn($v) => is_iterable($v),
            self::ARRAY => static fn($v) => is_array($v),
            self::OBJECT => static fn($v) => is_object($v),
            self::CALLABLE => static fn($v) => is_callable($v),
            self::NUMERIC => static fn($v) => is_numeric($v),
            self::COUNTABLE => static fn($v) => is_countable($v),
            self::RESOURCE => static fn($v) => is_resource($v),
            self::NULL => static fn($v) => is_null($v),
            default => throw new InvalidArgumentException("Unknown assert type `$type`")
        };
    }
}
