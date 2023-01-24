<?php declare(strict_types=1);

namespace Acelot\AutoMapper\Exception;

use Acelot\AutoMapper\MapperExceptionInterface;
use RuntimeException;

final class UnexpectedValueException extends RuntimeException implements MapperExceptionInterface
{
    public function __construct(
        private string $expectedType,
        private mixed $actualValue
    )
    {
        parent::__construct(
            sprintf('Unexpected value. Expected type `%s`, actual `%s`', $expectedType, $this->getActualType())
        );
    }

    public function getExpectedType(): string
    {
        return $this->expectedType;
    }

    public function getActualValue(): mixed
    {
        return $this->actualValue;
    }

    public function getActualType(): string
    {
        return is_object($this->actualValue)
            ? get_class($this->actualValue)
            : gettype($this->actualValue);
    }
}
