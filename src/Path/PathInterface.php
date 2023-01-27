<?php declare(strict_types=1);

namespace Acelot\AutoMapper\Path;

interface PathInterface
{
    /**
     * @return PartInterface[]
     */
    public function getParts(): array;
}
