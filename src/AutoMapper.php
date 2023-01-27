<?php declare(strict_types=1);

namespace Acelot\AutoMapper;

use Acelot\AutoMapper\Api\Fields;
use Acelot\AutoMapper\Api\Helpers;
use Acelot\AutoMapper\Api\Main;
use Acelot\AutoMapper\Api\Processors;
use Acelot\AutoMapper\Context\Context;
use Acelot\AutoMapper\Field\ToArrayKey;
use Acelot\AutoMapper\Field\ToObjectMethod;
use Acelot\AutoMapper\Field\ToObjectProp;
use Acelot\AutoMapper\Field\ToSelf;
use Acelot\AutoMapper\Processor\Call;
use Acelot\AutoMapper\Processor\CallWithContext;
use Acelot\AutoMapper\Processor\Condition;
use Acelot\AutoMapper\Processor\ConditionWithContext;
use Acelot\AutoMapper\Processor\Find;
use Acelot\AutoMapper\Processor\FindWithContext;
use Acelot\AutoMapper\Processor\Get;
use Acelot\AutoMapper\Processor\GetFromContext;
use Acelot\AutoMapper\Processor\Ignore;
use Acelot\AutoMapper\Processor\MapIterable;
use Acelot\AutoMapper\Processor\MarshalNestedArray;
use Acelot\AutoMapper\Processor\MarshalNestedObject;
use Acelot\AutoMapper\Processor\NotFound;
use Acelot\AutoMapper\Processor\Pass;
use Acelot\AutoMapper\Processor\Pipeline;
use Acelot\AutoMapper\Processor\Value;
use stdClass;

final class AutoMapper
{
    private static array $instances = array();

    public static function map(Context $context, mixed $source, mixed &$target, FieldInterface ...$fields): void
    {
        self::getInstance(Main::class)->map($context, $source, $target, ...$fields);
    }

    public static function marshalArray(Context $context, mixed $source, ToArrayKey ...$fields): array
    {
        return self::getInstance(Main::class)->marshalArray($context, $source, ...$fields);
    }

    public static function marshalObject(Context $context, mixed $source, ObjectFieldInterface ...$fields): stdClass
    {
        return self::getInstance(Main::class)->marshalObject($context, $source, ...$fields);
    }

    public static function toKey(int|string $key, ProcessorInterface $processor): ToArrayKey
    {
        return self::getInstance(Fields::class)->toKey($key, $processor);
    }

    public static function toProp(string $property, ProcessorInterface $processor): ToObjectProp
    {
        return self::getInstance(Fields::class)->toProp($property, $processor);
    }

    public static function toMethod(string $method, ProcessorInterface $processor): ToObjectMethod
    {
        return self::getInstance(Fields::class)->toMethod($method, $processor);
    }

    public static function toSelf(ProcessorInterface $processor): ToSelf
    {
        return self::getInstance(Fields::class)->toSelf($processor);
    }

    public static function call(callable $callable): Call
    {
        return self::getInstance(Processors::class)->call($callable);
    }

    public static function callCtx(callable $callable): CallWithContext
    {
        return self::getInstance(Processors::class)->callCtx($callable);
    }

    public static function condition(callable $condition, ProcessorInterface $true, ?ProcessorInterface $false = null): Condition
    {
        return self::getInstance(Processors::class)->condition($condition, $true, $false);
    }

    public static function conditionCtx(callable $condition, ProcessorInterface $true, ?ProcessorInterface $false = null): ConditionWithContext
    {
        return self::getInstance(Processors::class)->conditionCtx($condition, $true, $false);
    }

    public static function find(callable $predicate): Find
    {
        return self::getInstance(Processors::class)->find($predicate);
    }

    public static function findCtx(callable $predicate): FindWithContext
    {
        return self::getInstance(Processors::class)->findCtx($predicate);
    }

    public static function get(string $path): Get
    {
        return self::getInstance(Processors::class)->get($path);
    }

    public static function getFromCtx(string $key): GetFromContext
    {
        return self::getInstance(Processors::class)->getFromCtx($key);
    }

    public static function ignore(): Ignore
    {
        return self::getInstance(Processors::class)->ignore();
    }

    public static function mapIterable(ProcessorInterface $processor, bool $keepKeys = false): MapIterable
    {
        return self::getInstance(Processors::class)->mapIterable($processor, $keepKeys);
    }

    public static function marshalNestedArray(ToArrayKey $firstField, ToArrayKey ...$restFields): MarshalNestedArray
    {
        return self::getInstance(Processors::class)->marshalNestedArray($firstField, ...$restFields);
    }

    public static function marshalNestedObject(ToObjectProp $firstField, ToObjectProp ...$restFields): MarshalNestedObject
    {
        return self::getInstance(Processors::class)->marshalNestedObject($firstField, ...$restFields);
    }

    public static function notFound(string $path): NotFound
    {
        return self::getInstance(Processors::class)->notFound($path);
    }

    public static function value(mixed $value): Value
    {
        return self::getInstance(Processors::class)->value($value);
    }

    public static function pass(): Pass
    {
        return self::getInstance(Processors::class)->pass();
    }

    public static function pipe(ProcessorInterface ...$processors): Pipeline
    {
        return self::getInstance(Processors::class)->pipe(...$processors);
    }

    public static function joinArray(string $separator = ''): Condition
    {
        return self::getInstance(Helpers::class)->joinArray($separator);
    }

    public static function sortArray(bool $descending = false, int $options = SORT_REGULAR): Condition
    {
        return self::getInstance(Helpers::class)->sortArray($descending, $options);
    }

    public static function uniqueArray(bool $keepKeys = false, int $options = SORT_STRING): Condition
    {
        return self::getInstance(Helpers::class)->uniqueArray($keepKeys, $options);
    }

    public static function ifNotFound(ProcessorInterface $true, ?ProcessorInterface $false = null): Condition
    {
        return self::getInstance(Helpers::class)->ifNotFound($true, $false);
    }

    public static function ifEmpty(ProcessorInterface $true, ?ProcessorInterface $false = null): Condition
    {
        return self::getInstance(Helpers::class)->ifEmpty($true, $false);
    }

    public static function ifNull(ProcessorInterface $true, ?ProcessorInterface $false = null): Condition
    {
        return self::getInstance(Helpers::class)->ifNull($true, $false);
    }

    public static function ifEqual(mixed $to, ProcessorInterface $true, ?ProcessorInterface $false = null, bool $strict = true): Condition
    {
        return self::getInstance(Helpers::class)->ifEqual($to, $true, $false, $strict);
    }

    public static function ifNotEqual(mixed $to, ProcessorInterface $true, ?ProcessorInterface $false = null, bool $strict = true): Condition
    {
        return self::getInstance(Helpers::class)->ifNotEqual($to, $true, $false, $strict);
    }

    public static function explodeString(string $separator): Condition
    {
        return self::getInstance(Helpers::class)->explodeString($separator);
    }

    public static function trimString(string $characters = " \t\n\r\x00\v"): Condition
    {
        return self::getInstance(Helpers::class)->trimString($characters);
    }

    public static function toBool(): Call
    {
        return self::getInstance(Helpers::class)->toBool();
    }

    public static function toFloat(): Condition
    {
        return self::getInstance(Helpers::class)->toFloat();
    }

    public static function toInt(): Condition
    {
        return self::getInstance(Helpers::class)->toInt();
    }

    public static function toString(): Condition
    {
        return self::getInstance(Helpers::class)->toString();
    }

    public static function toArray(): Call
    {
        return self::getInstance(Helpers::class)->toArray();
    }

    /**
     * @template T
     * @param class-string<T> $class
     * @return T
     */
    private static function getInstance(string $class)
    {
        if (!isset(self::$instances[$class])) {
            self::$instances[$class] = new $class();
        }

        return self::$instances[$class];
    }
}
