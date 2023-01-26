<?php declare(strict_types=1);

namespace Acelot\AutoMapper;

use Acelot\AutoMapper\Context\Context;
use Acelot\AutoMapper\Exception\UnexpectedValueException;
use Acelot\AutoMapper\Path\Parser;
use Acelot\AutoMapper\Processor\Pass;
use Acelot\AutoMapper\Value\NotFoundValue;
use stdClass;
use Traversable;

#region Main
function map(Context $context, mixed $source, mixed &$target, FieldInterface ...$fields)
{
    $mapper = new Mapper($context, ...$fields);
    $mapper->map($source, $target);
}

function marshalArray(Context $context, mixed $source, Field\ToArrayKey ...$fields): array
{
    $target = [];

    $mapper = new Mapper($context, ...$fields);
    $mapper->map($source, $target);

    return $target;
}

function marshalObject(Context $context, mixed $source, ObjectFieldInterface ...$fields): stdClass
{
    $target = new stdClass();

    $mapper = new Mapper($context, ...$fields);
    $mapper->map($source, $target);

    return $target;
}

#endregion

#region Fields
function toKey(int|string $key, ProcessorInterface $processor): Field\ToArrayKey
{
    return new Field\ToArrayKey($key, $processor);
}

function toProp(string $property, ProcessorInterface $processor): Field\ToObjectProp
{
    return new Field\ToObjectProp($property, $processor);
}

function toMethod(string $method, ProcessorInterface $processor): Field\ToObjectMethod
{
    return new Field\ToObjectMethod($method, $processor);
}

function toSelf(ProcessorInterface $processor): Field\ToSelf
{
    return new Field\ToSelf($processor);
}

#endregion

#region Processors
function call(callable $callable): Processor\Call
{
    return new Processor\Call($callable);
}

function callCtx(callable $callable): Processor\CallWithContext
{
    return new Processor\CallWithContext($callable);
}

function condition(
    callable            $condition,
    ProcessorInterface  $true,
    ?ProcessorInterface $false = null
): Processor\Condition
{
    return new Processor\Condition($condition, $true, $false ?? new Pass());
}

function conditionCtx(
    callable            $condition,
    ProcessorInterface  $true,
    ?ProcessorInterface $false = null
): Processor\ConditionWithContext
{
    return new Processor\ConditionWithContext($condition, $true, $false ?? new Pass());
}

function find(callable $predicate): Processor\Find
{
    return new Processor\Find($predicate);
}

function findCtx(callable $predicate): Processor\FindWithContext
{
    return new Processor\FindWithContext($predicate);
}

function get(string $path): Processor\Get
{
    return new Processor\Get(new Parser(), new ExtractorResolver(), $path);
}

function getFromCtx(string $key): Processor\GetFromContext
{
    return new Processor\GetFromContext($key);
}

function ignore(): Processor\Ignore
{
    return new Processor\Ignore();
}

function mapIterable(ProcessorInterface $processor, bool $keepKeys = false): Processor\MapIterable
{
    return new Processor\MapIterable($processor, $keepKeys);
}

function marshalNestedArray(Field\ToArrayKey $firstField, Field\ToArrayKey ...$restFields): Processor\MarshalNestedArray
{
    return new Processor\MarshalNestedArray(new MapperFactory(), $firstField, ...$restFields);
}

function marshalNestedObject(Field\ToObjectProp $firstField, Field\ToObjectProp ...$restFields): Processor\MarshalNestedObject
{
    return new Processor\MarshalNestedObject(new MapperFactory(), $firstField, ...$restFields);
}

function notFound(string $path): Processor\NotFound
{
    return new Processor\NotFound($path);
}

function value(mixed $value): Processor\Value
{
    return new Processor\Value($value);
}

function pass(): Processor\Pass
{
    return new Processor\Pass();
}

function pipe(ProcessorInterface ...$processors): Processor\Pipeline
{
    return new Processor\Pipeline(...$processors);
}

#endregion

#region Helpers
function joinArray(string $separator = ''): Processor\Condition
{
    return condition(
        'is_array',
        true: call(fn($value) => implode($separator, $value)),
        false: throwUnexpectedValueException('array')
    );
}

function sortArray(bool $descending = false, int $options = SORT_REGULAR): Processor\Condition
{
    return condition(
        'is_array',
        true: call(function ($value) use ($descending, $options) {
            if ($descending) {
                rsort($value, $options);
            } else {
                sort($value, $options);
            }

            return $value;
        }),
        false: throwUnexpectedValueException('array')
    );
}

function uniqueArray(bool $keepKeys = false, int $options = SORT_STRING): Processor\Condition
{
    return condition(
        'is_array',
        true: call(function ($value) use ($keepKeys, $options) {
            $items = array_unique($value, $options);

            return $keepKeys ? $items : array_values($items);
        }),
        false: throwUnexpectedValueException('array')
    );
}

function ifNotFound(ProcessorInterface $true, ?ProcessorInterface $false = null): Processor\Condition
{
    return condition(fn($value) => $value instanceof NotFoundValue, $true, $false ?? pass());
}

function ifEmpty(ProcessorInterface $true, ?ProcessorInterface $false = null): Processor\Condition
{
    return condition(fn($value) => empty($value), $true, $false ?? pass());
}

function ifNull(ProcessorInterface $true, ?ProcessorInterface $false = null): Processor\Condition
{
    return condition('is_null', $true, $false ?? pass());
}

function ifLt(mixed $than, ProcessorInterface $true, ?ProcessorInterface $false = null): Processor\Condition
{
    return condition(fn($value) => $value < $than, $true, $false ?? pass());
}

function ifLte(mixed $than, ProcessorInterface $true, ?ProcessorInterface $false = null): Processor\Condition
{
    return condition(fn($value) => $value <= $than, $true, $false ?? pass());
}

function ifEqual(mixed $to, ProcessorInterface $true, ?ProcessorInterface $false = null, bool $strict = true): Processor\Condition
{
    return condition(fn($value) => $strict ? $value === $to : $value == $to, $true, $false ?? pass());
}

function ifNotEqual(mixed $to, ProcessorInterface $true, ?ProcessorInterface $false = null, bool $strict = true): Processor\Condition
{
    return condition(fn($value) => $strict ? $value !== $to : $value != $to, $true, $false ?? pass());
}

function ifGte(mixed $than, ProcessorInterface $true, ?ProcessorInterface $false = null): Processor\Condition
{
    return condition(fn($value) => $value >= $than, $true, $false ?? pass());
}

function ifGt(mixed $than, ProcessorInterface $true, ?ProcessorInterface $false = null): Processor\Condition
{
    return condition(fn($value) => $value > $than, $true, $false ?? pass());
}

function explodeString(string $separator): Processor\Condition
{
    return condition(
        'is_string',
        true: call(fn($value) => explode($separator, $value)),
        false: throwUnexpectedValueException('string')
    );
}

function trimString(string $characters = " \t\n\r\0\x0B"): Processor\Condition
{
    return condition(
        'is_string',
        true: call(fn($value) => trim($value, $characters)),
        false: throwUnexpectedValueException('string')
    );
}

function toBool(): Processor\Call
{
    return call('boolval');
}

function toFloat(bool $nullToZero = false): Processor\Condition
{
    return condition(
        fn($value) => is_null($value) || is_scalar($value),
        true: call(fn($value) => (is_null($value) && $nullToZero) ? 0.0 : floatval($value)),
        false: throwUnexpectedValueException('null|scalar')
    );
}

function toInt(bool $nullToZero = false): Processor\Condition
{
    return condition(
        fn($value) => is_null($value) || is_scalar($value),
        true: call(fn($value) => (is_null($value) && $nullToZero) ? 0 : intval($value)),
        false: throwUnexpectedValueException('null|scalar')
    );
}

function toString(): Processor\Condition
{
    return condition(
        fn($value) => is_null($value) || is_scalar($value) || (is_object($value) && method_exists($value, '__toString')),
        true: call('strval'),
        false: throwUnexpectedValueException('null|scalar|__toString()')
    );
}

function toArray(): Processor\Call
{
    return call(function ($value) {
        if ($value instanceof Traversable) {
            return iterator_to_array($value);
        }

        if (is_array($value)) {
            return $value;
        }

        return [$value];
    });
}

function throwUnexpectedValueException(string $expectedType): Processor\Call
{
    return call(fn($value) => throw new UnexpectedValueException($expectedType, $value));
}
#endregion
