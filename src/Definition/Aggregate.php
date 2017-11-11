<?php declare(strict_types=1);

namespace Acelot\AutoMapper\Definition;

use Acelot\AutoMapper\Definition\Traits\IgnoreEmptyTrait;
use Acelot\AutoMapper\Definition\Traits\TrimValueTrait;
use Acelot\AutoMapper\DefinitionInterface;
use Acelot\AutoMapper\Exception\IgnoreFieldException;
use Acelot\AutoMapper\SourceInterface;

final class Aggregate implements DefinitionInterface
{
    use TrimValueTrait;
    use IgnoreEmptyTrait;

    /**
     * @var callable
     */
    protected $aggregator;

    /**
     * @param callable $aggregator
     *
     * @return Aggregate
     */
    public static function create(callable $aggregator): Aggregate
    {
        return new Aggregate($aggregator);
    }

    /**
     * @param callable $aggregator
     */
    public function __construct(callable $aggregator)
    {
        $this->aggregator = $aggregator;
    }

    /**
     * @param SourceInterface $source
     *
     * @return mixed
     * @throws IgnoreFieldException
     */
    public function getValue(SourceInterface $source)
    {
        $value = call_user_func($this->aggregator, $source);

        if ($this->isTrim) {
            $value = trim($value);
        }

        if ($this->isIgnoreEmpty && empty($value)) {
            throw new IgnoreFieldException();
        }

        return $value;
    }
}
