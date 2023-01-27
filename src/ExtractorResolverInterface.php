<?php declare(strict_types=1);

namespace Acelot\AutoMapper;

use Acelot\AutoMapper\Path\PartInterface;

interface ExtractorResolverInterface
{
    public function resolve(PartInterface $part): ExtractorInterface;
}
