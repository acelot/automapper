<?php declare(strict_types=1);

namespace Acelot\AutoMapper\Definition;

use Acelot\AutoMapper\DefinitionInterface;
use Acelot\AutoMapper\Exception\IgnoreFieldException;
use Acelot\AutoMapper\SourceInterface;

final class Ignore implements DefinitionInterface
{
    /**
     * @return Ignore
     */
    public static function create(): Ignore
    {
        return new Ignore();
    }

    /**
     * @param SourceInterface $source
     *
     * @throws IgnoreFieldException
     */
    public function getValue(SourceInterface $source)
    {
        throw new IgnoreFieldException();
    }
}
