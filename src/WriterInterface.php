<?php declare(strict_types=1);

namespace Acelot\AutoMapper;

interface WriterInterface
{
    /**
     * @param mixed  $target
     * @param string $field
     * @param mixed  $value
     */
    public static function set(&$target, string $field, $value): void;
}
