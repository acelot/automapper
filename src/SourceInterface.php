<?php declare(strict_types=1);

namespace Acelot\AutoMapper;

interface SourceInterface
{
    /**
     * @return mixed
     */
    public function raw();

    /**
     * @param string $field
     *
     * @return bool
     */
    public function has(string $field): bool;

    /**
     * @param string $field
     * @param mixed  $default
     *
     * @return mixed
     */
    public function get(string $field, $default = null);
}
