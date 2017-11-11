<?php declare(strict_types=1);

namespace Acelot\AutoMapper\Source;

use Acelot\AutoMapper\SourceInterface;

class ArraySource implements SourceInterface
{
    /**
     * @var array|\ArrayAccess
     */
    protected $storage;

    /**
     * @param array|\ArrayAccess $storage
     */
    public function __construct($storage)
    {
        if (!is_array($storage) && !$storage instanceof \ArrayAccess) {
            throw new \InvalidArgumentException('Storage must be an array or implement ArrayAccess interface');
        }

        $this->storage = $storage;
    }

    /**
     * @return array
     */
    public function getData(): array
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
        if (is_array($this->storage)) {
            return array_key_exists($field, $this->storage);
        }

        return $this->storage->offsetExists($field);
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

        return $this->storage[$field];
    }
}
