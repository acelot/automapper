<?php declare(strict_types=1);

namespace Acelot\AutoMapper;

interface MapperInterface
{
    public function map(mixed $source, mixed &$target): void;
}
