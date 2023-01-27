<?php declare(strict_types=1);

namespace Acelot\AutoMapper\Api;

use Acelot\AutoMapper\ExtractorResolver;
use Acelot\AutoMapper\Field\ToArrayKey;
use Acelot\AutoMapper\Field\ToObjectProp;
use Acelot\AutoMapper\MapperFactory;
use Acelot\AutoMapper\Path\Parser;
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
use Acelot\AutoMapper\ProcessorInterface;

final class Processors
{
    public function call(callable $callable): Call
    {
        return new Call($callable);
    }

    public function callCtx(callable $callable): CallWithContext
    {
        return new CallWithContext($callable);
    }

    public function condition(callable $condition, ProcessorInterface  $true, ?ProcessorInterface $false = null): Condition
    {
        return new Condition($condition, $true, $false ?? new Pass());
    }

    public function conditionCtx(callable $condition, ProcessorInterface $true, ?ProcessorInterface $false = null): ConditionWithContext
    {
        return new ConditionWithContext($condition, $true, $false ?? new Pass());
    }

    public function find(callable $predicate): Find
    {
        return new Find($predicate);
    }

    public function findCtx(callable $predicate): FindWithContext
    {
        return new FindWithContext($predicate);
    }

    public function get(string $path): Get
    {
        return new Get(new Parser(), new ExtractorResolver(), $path);
    }

    public function getFromCtx(string $key): GetFromContext
    {
        return new GetFromContext($key);
    }

    public function ignore(): Ignore
    {
        return new Ignore();
    }

    public function mapIterable(ProcessorInterface $processor, bool $keepKeys = false): MapIterable
    {
        return new MapIterable($processor, $keepKeys);
    }

    public function marshalNestedArray(ToArrayKey $firstField, ToArrayKey ...$restFields): MarshalNestedArray
    {
        return new MarshalNestedArray(new MapperFactory(), $firstField, ...$restFields);
    }

    public function marshalNestedObject(ToObjectProp $firstField, ToObjectProp ...$restFields): MarshalNestedObject
    {
        return new MarshalNestedObject(new MapperFactory(), $firstField, ...$restFields);
    }

    public function notFound(string $path): NotFound
    {
        return new NotFound($path);
    }

    public function value(mixed $value): Value
    {
        return new Value($value);
    }

    public function pass(): Pass
    {
        return new Pass();
    }

    public function pipe(ProcessorInterface ...$processors): Pipeline
    {
        return new Pipeline(...$processors);
    }
}
