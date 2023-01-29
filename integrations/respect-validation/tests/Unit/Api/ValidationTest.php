<?php declare(strict_types=1);

namespace Acelot\AutoMapper\Integrations\RespectValidation\Tests\Unit\Api;

use Acelot\AutoMapper\Integrations\RespectValidation\Api\Validation;
use Acelot\AutoMapper\Integrations\RespectValidation\Processor\Validate;
use Acelot\AutoMapper\Integrations\RespectValidation\Value\ValidationFailedValue;
use Acelot\AutoMapper\Processor\Condition;
use Acelot\AutoMapper\ProcessorInterface;
use PHPUnit\Framework\TestCase;
use Respect\Validation\Exceptions\ValidationException;
use Respect\Validation\Validatable;

/**
 * @covers \Acelot\AutoMapper\Integrations\RespectValidation\Api\Validation
 */
final class ValidationTest extends TestCase
{
    public function testValidate_PassedValidatable_ReturnsValidateProcessor(): void
    {
        $validatable = $this->createMock(Validatable::class);

        $processor = (new Validation())->validate($validatable);

        self::assertInstanceOf(Validate::class, $processor);
        self::assertSame($validatable, $processor->getValidator());
    }

    public function testIfValidationFailed_PassedSubProcessors_ReturnsConditionProcessor(): void
    {
        $true = $this->createMock(ProcessorInterface::class);
        $false = $this->createMock(ProcessorInterface::class);

        $processor = (new Validation())->ifValidationFailed($true, $false);
        $exception = $this->createMock(ValidationException::class);

        self::assertInstanceOf(Condition::class, $processor);
        self::assertTrue(($processor->getCondition())(new ValidationFailedValue($exception)));
        self::assertSame($true, $processor->getTrueProcessor());
        self::assertSame($false, $processor->getFalseProcessor());
    }
}
