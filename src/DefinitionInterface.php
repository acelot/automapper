<?php declare(strict_types=1);

namespace Acelot\AutoMapper;

use Acelot\AutoMapper\Exception\IgnoreFieldException;
use Acelot\AutoMapper\Exception\SourceFieldMissingException;

interface DefinitionInterface
{
    /**
     * @param SourceInterface $source
     *
     * @return mixed
     * @throws IgnoreFieldException
     * @throws SourceFieldMissingException
     */
    public function getValue(SourceInterface $source);
}
