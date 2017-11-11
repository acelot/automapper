<?php declare(strict_types=1);

namespace Acelot\AutoMapper\Definition\Traits;

trait TrimValueTrait
{
    /**
     * @var bool
     */
    protected $isTrim = false;

    /**
     * @return $this
     */
    public function trim()
    {
        $this->isTrim = true;
        return $this;
    }
}
