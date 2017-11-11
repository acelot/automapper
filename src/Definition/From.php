<?php declare(strict_types=1);

namespace Acelot\AutoMapper\Definition;

use Acelot\AutoMapper\Definition\Traits\DefaultValueTrait;
use Acelot\AutoMapper\Definition\Traits\IgnoreEmptyTrait;
use Acelot\AutoMapper\Definition\Traits\IgnoreMissingTrait;
use Acelot\AutoMapper\Definition\Traits\TrimValueTrait;
use Acelot\AutoMapper\DefinitionInterface;
use Acelot\AutoMapper\Exception\IgnoreFieldException;
use Acelot\AutoMapper\Exception\SourceFieldMissingException;
use Acelot\AutoMapper\SourceInterface;

final class From implements DefinitionInterface
{
    use DefaultValueTrait;
    use TrimValueTrait;
    use IgnoreMissingTrait;
    use IgnoreEmptyTrait;

    /**
     * @var string
     */
    protected $field;

    /**
     * @var callable
     */
    protected $converter;

    /**
     * @param string $field
     *
     * @return From
     */
    public static function create(string $field): From
    {
        return new From($field);
    }

    /**
     * @param string $field
     */
    public function __construct(string $field)
    {
        $this->field = $field;
    }

    /**
     * @param callable $converter
     *
     * @return $this
     */
    public function convert(callable $converter)
    {
        $this->converter = $converter;
        return $this;
    }

    /**
     * @param SourceInterface $source
     *
     * @return mixed
     * @throws IgnoreFieldException
     * @throws SourceFieldMissingException
     */
    public function getValue(SourceInterface $source)
    {
        if (!$source->has($this->field)) {
            if ($this->isIgnoreMissing) {
                throw new IgnoreFieldException();
            }

            if ($this->isDefaultValueSet) {
                return $this->defaultValue;
            }

            throw new SourceFieldMissingException(sprintf('Field "%s" does not exists in source', $this->field));
        }

        $value = $source->get($this->field);

        if ($this->converter) {
            $value = call_user_func($this->converter, $value);
        }

        if ($this->isTrim) {
            $value = trim($value);
        }

        if ($this->isIgnoreEmpty && empty($value)) {
            throw new IgnoreFieldException();
        }

        return $value;
    }
}
