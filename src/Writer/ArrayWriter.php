<?php declare(strict_types=1);

namespace Acelot\AutoMapper\Writer;

use Acelot\AutoMapper\WriterInterface;

class ArrayWriter implements WriterInterface
{
    /**
     * @param array  $target
     * @param string $field
     * @param mixed  $value
     */
    public static function set(&$target, string $field, $value): void
    {
        $target[$field] = $value;
    }
}