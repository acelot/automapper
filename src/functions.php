<?php declare(strict_types=1);

namespace Acelot\AutoMapper;

use Acelot\AutoMapper\Definition\Aggregate;
use Acelot\AutoMapper\Definition\From;
use Acelot\AutoMapper\Definition\Ignore;
use Acelot\AutoMapper\Definition\Value;

/**
 * @param string              $name
 * @param DefinitionInterface $definition
 *
 * @return Field
 */
function field(string $name, DefinitionInterface $definition): Field
{
    return new Field($name, $definition);
}

/**
 * @param callable $aggregator
 *
 * @return Aggregate
 */
function aggregate(callable $aggregator): Aggregate
{
    return new Aggregate($aggregator);
}

/**
 * @param string $field
 *
 * @return From
 */
function from(string $field): From
{
    return new From($field);
}

/**
 * @return Ignore
 */
function ignore(): Ignore
{
    return new Ignore();
}

/**
 * @param $value
 *
 * @return Value
 */
function value($value): Value
{
    return new Value($value);
}
