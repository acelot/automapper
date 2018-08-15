<?php declare(strict_types=1);

namespace Acelot\AutoMapper\Source;

use Acelot\AutoMapper\SourceInterface;

class ObjectSource implements SourceInterface
{
    /**
     * @var object
     */
    protected $storage;

    /**
     * @param object $source
     */
    public function __construct($source)
    {
        if (!is_object($source)) {
            throw new \InvalidArgumentException('Storage must be an object');
        }

        $this->storage = $source;
    }

    /**
     * @return object
     */
    public function getData()
    {
        return $this->storage;
    }

    /**
     * @return mixed
     */
    public function raw()
    {
        return $this->storage;
    }

    /**
     * @param string $field
     *
     * @return bool
     */
    public function has(string $field): bool
    {
        return property_exists($this->storage, $field);
    }

    /**
     * @param string $field
     * @param mixed  $default
     *
     * @return mixed
     */
    public function get(string $field, $default = null)
    {
        if (!$this->has($field)) {
            return $default;
        }

        return $this->storage->$field;
    }
}
