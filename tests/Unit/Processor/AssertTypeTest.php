<?php declare(strict_types=1);

namespace Acelot\AutoMapper\Tests\Unit\Processor;

use Acelot\AutoMapper\Context\ContextInterface;
use Acelot\AutoMapper\Exception\UnexpectedValueException;
use Acelot\AutoMapper\Processor\AssertType;
use Acelot\AutoMapper\Tests\Fixtures\TestClass;
use Acelot\AutoMapper\Value\UserValue;
use Acelot\AutoMapper\ValueInterface;
use ArrayIterator;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use stdClass;

/**
 * @covers \Acelot\AutoMapper\Processor\AssertType
 */
final class AssertTypeTest extends TestCase
{
    public function testGetOneOfTypes_Constructed_ReturnsOneOfTypes(): void
    {
        $processor = new AssertType('one', 'two');

        self::assertSame(['one', 'two'], $processor->getOneOfTypes());
    }

    public function testProcess_PassedNotUserValue_ReturnsSameValue(): void
    {
        $context = $this->createMock(ContextInterface::class);
        $value = $this->createMock(ValueInterface::class);

        $processor = new AssertType('test');

        self::assertSame($value, $processor->process($context, $value));
    }

    public function testProcess_PassedNotExistingType_ThrowsInvalidArgumentException(): void
    {
        $context = $this->createMock(ContextInterface::class);
        $value = new UserValue('test');

        $processor = new AssertType('test');

        self::expectExceptionObject(new InvalidArgumentException('Unknown assert type `test`'));

        $processor->process($context, $value);
    }

    /**
     * @param string $type
     * @param mixed $userValue
     * @return void
     *
     * @dataProvider typeAndValuesProvider
     */
    public function testProcess_PassedExistingTypeAndValidValue_ReturnsSameValue(string $type, mixed $userValue): void
    {
        $context = $this->createMock(ContextInterface::class);
        $value = new UserValue($userValue);

        $processor = new AssertType($type);

        self::assertSame($value, $processor->process($context, $value));
    }

    public function typeAndValuesProvider(): array
    {
        return [
            ['bool', true],
            ['int', 1],
            ['float', 1.0],
            ['string', 'test'],
            ['__toString', new TestClass(0, '', 0)],
            ['iterable', []],
            ['iterable', new ArrayIterator([])],
            ['array', []],
            ['object', new TestClass(0, '', 0)],
            ['object', new stdClass()],
            ['scalar', 1],
            ['scalar', 1.0],
            ['scalar', false],
            ['callable', fn() => true],
            ['callable', [new TestClass(0, '', 0), 'getPrice']],
            ['callable', 'is_string'],
            ['numeric', '0'],
            ['numeric', '0.1'],
            ['numeric', 1],
            ['countable', []],
            ['countable', new ArrayIterator([])],
            ['resource', STDOUT],
            ['null', null],
        ];
    }

    public function testProcess_PassedMultipleTypes_ReturnsSameValue(): void
    {
        $context = $this->createMock(ContextInterface::class);
        $value = new UserValue('test');

        $processor = new AssertType('null', 'int', 'string');

        self::assertSame($value, $processor->process($context, $value));
    }

    public function testProcess_PassedExistingTypeAndInvalidValue_ThrowsUnexpectedValueException(): void
    {
        $context = $this->createMock(ContextInterface::class);
        $value = new UserValue(1);

        $processor = new AssertType('string');

        self::expectExceptionObject(new UnexpectedValueException('string', 1));

        $processor->process($context, $value);
    }

    public function testProcess_PassedExistingMultipleTypeAndInvalidValue_ThrowsUnexpectedValueException(): void
    {
        $context = $this->createMock(ContextInterface::class);
        $value = new UserValue(1);

        $processor = new AssertType('null', 'string', '__toString');

        self::expectExceptionObject(new UnexpectedValueException('null|string|__toString', 1));

        $processor->process($context, $value);
    }
}
