<?php declare(strict_types=1);

namespace Acelot\AutoMapper\Path;

interface ParserInterface
{
    public function parse(string $path): PathInterface;
}
