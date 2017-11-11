<?php declare(strict_types=1);

namespace Acelot\AutoMapper\Definition\Traits;

trait DefaultValueTrait
{
    /**
     * @var mixed
     */
    protected $defaultValue = null;

    /**
     * @var bool
     */
    protected $isDefaultValueSet = false;

    /**
     * @return mixed
     */
    public function getDefaultValue()
    {
        return $this->defaultValue;
    }

    /**
     * @param mixed $value
     *
     * @return $this
     */
    public function default($value)
    {
        $this->defaultValue = $value;
        $this->isDefaultValueSet = true;
        return $this;
    }

    /**
     * @return $this
     */
    public function withoutDefault()
    {
        $this->defaultValue = null;
        $this->isDefaultValueSet = false;
        return $this;
    }
}
