<?php declare(strict_types=1);

namespace Acelot\AutoMapper\Api;

use Acelot\AutoMapper\Processor\AssertType;
use Acelot\AutoMapper\Processor\Call;
use Acelot\AutoMapper\Processor\Condition;
use Acelot\AutoMapper\Processor\Pass;
use Acelot\AutoMapper\Processor\Pipeline;
use Acelot\AutoMapper\ProcessorInterface;
use Acelot\AutoMapper\Value\NotFoundValue;
use Traversable;

final class Helpers
{
    public function joinArray(string $separator = ''): Pipeline
    {
        return new Pipeline(
            new AssertType(AssertType::ARRAY),
            new Call(fn($value) => implode($separator, $value))
        );
    }

    public function sortArray(bool $descending = false, int $options = SORT_REGULAR): Pipeline
    {
        return new Pipeline(
            new AssertType(AssertType::ARRAY),
            new Call(function ($value) use ($descending, $options) {
                if ($descending) {
                    rsort($value, $options);
                } else {
                    sort($value, $options);
                }

                return $value;
            })
        );
    }

    public function uniqueArray(bool $keepKeys = false, int $options = SORT_STRING): Pipeline
    {
        return new Pipeline(
            new AssertType(AssertType::ARRAY),
            new Call(function ($value) use ($keepKeys, $options) {
                $items = array_unique($value, $options);

                return $keepKeys ? $items : array_values($items);
            })
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

    public function explodeString(string $separator): Pipeline
    {
        return new Pipeline(
            new AssertType(AssertType::STRING),
            new Call(fn($value) => explode($separator, $value))
        );
    }

    public function trimString(string $characters = " \t\n\r\0\x0B"): Pipeline
    {
        return new Pipeline(
            new AssertType(AssertType::STRING),
            new Call(fn($value) => trim($value, $characters))
        );
    }

    public function toBool(): Call
    {
        return  new Call('boolval');
    }

    public function toFloat(): Pipeline
    {
        return new Pipeline(
            new AssertType(AssertType::NULL, AssertType::SCALAR),
            new Call(fn($value) => is_null($value) ? 0.0 : floatval($value))
        );
    }

    public function toInt(): Pipeline
    {
        return new Pipeline(
            new AssertType(AssertType::NULL, AssertType::SCALAR),
            new Call(fn($value) => is_null($value) ? 0 : intval($value))
        );
    }

    public function toString(): Pipeline
    {
        return new Pipeline(
            new AssertType(AssertType::NULL, AssertType::SCALAR, AssertType::TO_STRING),
            new Call('strval')
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
}
