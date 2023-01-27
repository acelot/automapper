<?php declare(strict_types=1);

namespace Acelot\AutoMapper\Api;

use Acelot\AutoMapper\Exception\UnexpectedValueException;
use Acelot\AutoMapper\Processor\Call;
use Acelot\AutoMapper\Processor\Condition;
use Acelot\AutoMapper\Processor\Pass;
use Acelot\AutoMapper\ProcessorInterface;
use Acelot\AutoMapper\Value\NotFoundValue;
use Traversable;

final class Helpers
{
    public function joinArray(string $separator = ''): Condition
    {
        return new Condition(
            'is_array',
            true:  new Call(fn($value) => implode($separator, $value)),
            false: $this->throwUnexpectedValueException('array')
        );
    }

    public function sortArray(bool $descending = false, int $options = SORT_REGULAR): Condition
    {
        return new Condition(
            'is_array',
            true:  new Call(function ($value) use ($descending, $options) {
                if ($descending) {
                    rsort($value, $options);
                } else {
                    sort($value, $options);
                }

                return $value;
            }),
            false: $this->throwUnexpectedValueException('array')
        );
    }

    public function uniqueArray(bool $keepKeys = false, int $options = SORT_STRING): Condition
    {
        return new Condition(
            'is_array',
            true:  new Call(function ($value) use ($keepKeys, $options) {
                $items = array_unique($value, $options);

                return $keepKeys ? $items : array_values($items);
            }),
            false: $this->throwUnexpectedValueException('array')
        );
    }

    public function ifNotFound(ProcessorInterface $true, ?ProcessorInterface $false = null): Condition
    {
        return new Condition(fn($value) => $value instanceof NotFoundValue, $true, $false ?? new Pass());
    }

    public function ifEmpty(ProcessorInterface $true, ?ProcessorInterface $false = null): Condition
    {
        return new Condition(fn($value) => empty($value), $true, $false ?? new Pass());
    }

    public function ifNull(ProcessorInterface $true, ?ProcessorInterface $false = null): Condition
    {
        return new Condition('is_null', $true, $false ?? new Pass());
    }

    public function ifEqual(mixed $to, ProcessorInterface $true, ?ProcessorInterface $false = null, bool $strict = true): Condition
    {
        return new Condition(fn($value) => $strict ? $value === $to : $value == $to, $true, $false ?? new Pass());
    }

    public function ifNotEqual(mixed $to, ProcessorInterface $true, ?ProcessorInterface $false = null, bool $strict = true): Condition
    {
        return $this->ifEqual($to, $false, $true, $strict);
    }

    public function explodeString(string $separator): Condition
    {
        return new Condition(
            'is_string',
            true:  new Call(fn($value) => explode($separator, $value)),
            false: $this->throwUnexpectedValueException('string')
        );
    }

    public function trimString(string $characters = " \t\n\r\0\x0B"): Condition
    {
        return new Condition(
            'is_string',
            true:  new Call(fn($value) => trim($value, $characters)),
            false: $this->throwUnexpectedValueException('string')
        );
    }

    public function toBool(): Call
    {
        return  new Call('boolval');
    }

    public function toFloat(): Condition
    {
        return new Condition(
            fn($value) => is_null($value) || is_scalar($value),
            true:  new Call(fn($value) => is_null($value) ? 0.0 : floatval($value)),
            false: $this->throwUnexpectedValueException('null|scalar')
        );
    }

    public function toInt(): Condition
    {
        return new Condition(
            fn($value) => is_null($value) || is_scalar($value),
            true:  new Call(fn($value) => is_null($value) ? 0 : intval($value)),
            false: $this->throwUnexpectedValueException('null|scalar')
        );
    }

    public function toString(): Condition
    {
        return new Condition(
            fn($value) => is_null($value) || is_scalar($value) || (is_object($value) && method_exists($value, '__toString')),
            true:  new Call('strval'),
            false: $this->throwUnexpectedValueException('null|scalar|__toString()')
        );
    }

    public function toArray(): Call
    {
        return  new Call(function ($value) {
            if ($value instanceof Traversable) {
                return iterator_to_array($value);
            }

            if (is_array($value)) {
                return $value;
            }

            return [$value];
        });
    }

    private function throwUnexpectedValueException(string $expectedType): Call
    {
        return new Call(fn($value) => throw new UnexpectedValueException($expectedType, $value));
    }
}
