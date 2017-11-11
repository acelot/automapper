<?php declare(strict_types=1);

namespace Acelot\AutoMapper\Definition\Traits;

trait IgnoreMissingTrait
{
    /**
     * @var bool
     */
    protected $isIgnoreMissing = false;

    /**
     * @param bool $ignore
     *
     * @return $this
     */
    public function ignoreMissing(bool $ignore = true)
    {
        $this->isIgnoreMissing = $ignore;
        return $this;
    }
}
