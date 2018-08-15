<?php declare(strict_types=1);

namespace Acelot\AutoMapper;

use Acelot\AutoMapper\Exception\IgnoreFieldException;
use Acelot\AutoMapper\Exception\InvalidSourceException;
use Acelot\AutoMapper\Exception\InvalidTargetException;
use Acelot\AutoMapper\Exception\SourceFieldMissingException;
use Acelot\AutoMapper\Source\ArraySource;
use Acelot\AutoMapper\Source\ObjectSource;
use Acelot\AutoMapper\Writer\ArrayWriter;
use Acelot\AutoMapper\Writer\ObjectWriter;

class AutoMapper
{
    /**
     * @var Field[]
     */
    protected $fields = [];

    /**
     * @var bool
     */
    protected $ignoreAllMissing;

    /**
     * @param Field ...$fields
     *
     * @return AutoMapper
     */
    public static function create(Field ...$fields): AutoMapper
    {
        return new AutoMapper(...$fields);
    }

    /**
     * @param Field ...$fields
     */
    public function __construct(Field ...$fields)
    {
        foreach ($fields as $field) {
            $this->fields[$field->getName()] = $field;
        }
    }

    /**
     * @param string $name
     *
     * @return bool
     */
    public function hasField(string $name): bool
    {
        return array_key_exists($name, $this->fields);
    }

    /**
     * @param string $name
     *
     * @return Field
     */
    public function getField(string $name): Field
    {
        if (!$this->hasField($name)) {
            throw new \OutOfBoundsException(sprintf('Field "%s" not found', $name));
        }

        return $this->fields[$name];
    }

    /**
     * @return Field[]
     */
    public function getAllFields(): array
    {
        return $this->fields;
    }

    /**
     * @param Field $field
     *
     * @return static
     */
    public function withField(Field $field)
    {
        $clone = clone $this;
        $clone->fields[$field->getName()] = $field;
        return $clone;
    }

    /**
     * @param string $name
     *
     * @return static
     */
    public function withoutField(string $name)
    {
        $clone = clone $this;
        unset($clone->fields[$name]);
        return $clone;
    }

    /**
     * @param bool $ignore
     *
     * @return static
     */
    public function ignoreAllMissing(bool $ignore = true)
    {
        $clone = clone $this;
        $clone->ignoreAllMissing = $ignore;
        return $clone;
    }

    /**
     * @param array|object $source
     * @param array|object $target
     *
     * @throws InvalidSourceException
     * @throws InvalidTargetException
     * @throws SourceFieldMissingException
     */
    public function map($source, &$target)
    {
        $source = static::makeSource($source);
        $writer = static::makeWriter($target);

        foreach ($this->fields as $field) {
            try {
                $writer::set($target, $field->getName(), $field->getDefinition()->getValue($source));
            } catch (SourceFieldMissingException $e) {
                if ($this->ignoreAllMissing) {
                    continue;
                }
                throw $e;
            } catch (IgnoreFieldException $e) {
                continue;
            }
        }
    }

    /**
     * @param array|object $source
     *
     * @return array
     * @throws InvalidSourceException
     * @throws InvalidTargetException
     * @throws SourceFieldMissingException
     */
    public function marshalArray($source): array
    {
        $target = [];
        $this->map($source, $target);
        return $target;
    }

    /**
     * @param array|object $source
     *
     * @return object
     * @throws InvalidSourceException
     * @throws InvalidTargetException
     * @throws SourceFieldMissingException
     */
    public function marshalObject($source)
    {
        $target = new \stdClass();
        $this->map($source, $target);
        return $target;
    }

    /**
     * @param mixed $source
     *
     * @return SourceInterface
     * @throws InvalidSourceException
     */
    protected static function makeSource($source): SourceInterface
    {
        if (is_array($source) || $source instanceof \ArrayAccess) {
            return new ArraySource($source);
        }

        if (is_object($source)) {
            return new ObjectSource($source);
        }

        throw new InvalidSourceException(
            sprintf('Source type "%s" cannot be recognized as suitable storage', gettype($source))
        );
    }

    /**
     * @param mixed $target
     *
     * @return WriterInterface
     * @throws InvalidTargetException
     */
    protected static function makeWriter($target): WriterInterface
    {
        if (is_array($target) || $target instanceof \ArrayAccess) {
            return new ArrayWriter();
        }

        if (is_object($target)) {
            return new ObjectWriter();
        }

        throw new InvalidTargetException(
            sprintf('Cannot find suitable writer for target type "%s"', gettype($target))
        );
    }
}
