<?php declare(strict_types=1);

namespace Acelot\AutoMapper\Definition\Traits;

trait IgnoreEmptyTrait
{
    /**
     * @var bool
     */
    protected $isIgnoreEmpty = false;

    /**
     * @param bool $ignore
     *
     * @return $this
     */
    public function ignoreEmpty(bool $ignore = true)
    {
        $this->isIgnoreEmpty = $ignore;
        return $this;
    }
}
