<?php declare(strict_types=1);

namespace Acelot\AutoMapper\Tests\Unit\Exception;

use Acelot\AutoMapper\Exception\NotFoundException;
use Acelot\AutoMapper\Exception\UnexpectedValueException;
use Exception;
use PHPUnit\Framework\TestCase;
use stdClass;

/**
 * @covers \Acelot\AutoMapper\Exception\UnexpectedValueException
 */
final class UnexpectedValueExceptionTest extends TestCase
{
    public function testGetExpectedType_Constructed_ReturnsCorrectValue(): void
    {
        $exception = new UnexpectedValueException('expected_type', 'actual_value');

        self::assertSame('expected_type', $exception->getExpectedType());
    }

    public function testGetActualValue_Constructed_ReturnsCorrectValue(): void
    {
        $exception = new UnexpectedValueException('expected_type', 'actual_value');

        self::assertSame('actual_value', $exception->getActualValue());
    }

    /**
     * @param mixed $value
     * @param string $expectedActualType
     *
     * @dataProvider typesProvider
     */
    public function testGetActualType_Constructed_ReturnsCorrectValue(mixed $value, string $expectedActualType): void
    {
        $exception = new UnexpectedValueException('expected_type', $value);

        self::assertSame($expectedActualType, $exception->getActualType());
    }

    public function typesProvider(): array
    {
        return [
            [null, 'NULL'],
            [false, 'boolean'],
            [true, 'boolean'],
            [0, 'integer'],
            [1.1, 'double'],
            ['test', 'string'],
            [[], 'array'],
            [new stdClass(), 'stdClass'],
            [STDOUT, 'resource'],
            [fn() => true, 'Closure'],
            [function () {}, 'Closure'],
            [new self(), self::class],
        ];
    }

    public function testGetMessage_Constructed_ReturnCorrectMessage(): void
    {
        $exception = new UnexpectedValueException('expected_type', true);

        self::assertSame(
            'Unexpected value. Expected type `expected_type`, actual `boolean`',
            $exception->getMessage()
        );
    }
}
