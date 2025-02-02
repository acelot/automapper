<?php declare(strict_types=1);

namespace Acelot\AutoMapper\Path;

final class Path implements PathInterface
{
    /**
     * @var PartInterface[]
     */
    private array $parts;

    public function __construct(PartInterface ...$parts)
    {
        $this->parts = $parts;
    }

    /**
     * @return PartInterface[]
     */
    public function getParts(): array
    {
        return $this->parts;
    }
}
