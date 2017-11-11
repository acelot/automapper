<?php declare(strict_types=1);

namespace Acelot\AutoMapper\Definition;

use Acelot\AutoMapper\Definition\Traits\IgnoreEmptyTrait;
use Acelot\AutoMapper\Definition\Traits\TrimValueTrait;
use Acelot\AutoMapper\DefinitionInterface;
use Acelot\AutoMapper\Exception\IgnoreFieldException;
use Acelot\AutoMapper\SourceInterface;

final class Value implements DefinitionInterface
{
    use TrimValueTrait;
    use IgnoreEmptyTrait;

    /**
     * @var mixed
     */
    protected $value;

    /**
     * @param mixed $value
     *
     * @return Value
     */
    public static function create($value): Value
    {
        return new Value($value);
    }

    /**
     * @param mixed $value
     */
    public function __construct($value)
    {
        $this->value = $value;
    }

    /**
     * @param SourceInterface $source
     *
     * @return mixed
     */
    public function getValue(SourceInterface $source)
    {
        $value = $this->value;

        if ($this->isTrim) {
            $value = trim($value);
        }

        if ($this->isIgnoreEmpty && empty($value)) {
            throw new IgnoreFieldException();
        }

        return $value;
    }
}
