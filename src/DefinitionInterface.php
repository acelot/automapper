<?php declare(strict_types=1);

namespace Acelot\AutoMapper;

interface DefinitionInterface
{
    /**
     * @param SourceInterface $source
     *
     * @return mixed
     */
    public function getValue(SourceInterface $source);
}
