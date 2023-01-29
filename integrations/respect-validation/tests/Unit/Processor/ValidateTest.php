<?php declare(strict_types=1);

namespace Acelot\AutoMapper\Integrations\RespectValidation\Tests\Unit\Processor;

use Acelot\AutoMapper\ContextInterface;
use Acelot\AutoMapper\Integrations\RespectValidation\Processor\Validate;
use Acelot\AutoMapper\Integrations\RespectValidation\ValidationContextFactoryInterface;
use Acelot\AutoMapper\Integrations\RespectValidation\ValidationContextInterface;
use Acelot\AutoMapper\Integrations\RespectValidation\Value\ValidationFailedValue;
use Acelot\AutoMapper\Value\UserValue;
use Acelot\AutoMapper\ValueInterface;
use PHPUnit\Framework\TestCase;
use Respect\Validation\Exceptions\ValidationException;
use Respect\Validation\Validatable;

/**
 * @covers \Acelot\AutoMapper\Integrations\RespectValidation\Processor\Validate
 */
final class ValidateTest extends TestCase
{
    public function testGetValidator_Constructed_ReturnsSameValidator(): void
    {
        $validationContextFactory = $this->createMock(ValidationContextFactoryInterface::class);
        $validator = $this->createMock(Validatable::class);

        $processor = new Validate($validationContextFactory, $validator);

        self::assertSame($validator, $processor->getValidator());
    }

    public function testProcess_NotUserValuePassed_NeverCallsValidatorAssertMethod(): void
    {
        $context = $this->createMock(ContextInterface::class);
        $validationContextFactory = $this->createMock(ValidationContextFactoryInterface::class);
        $validator = $this->createMock(Validatable::class);
        $value = $this->createMock(ValueInterface::class);

        $processor = new Validate($validationContextFactory, $validator);

        $validator
            ->expects(self::never())
            ->method('assert');

        $processor->process($context, $value);
    }

    public function testProcess_UserValuePassed_CallsValidatorAssertMethod(): void
    {
        $context = $this->createMock(ContextInterface::class);
        $validationContextFactory = $this->createMock(ValidationContextFactoryInterface::class);
        $validationContext = $this->createMock(ValidationContextInterface::class);

        $validationContextFactory
            ->method('create')
            ->willReturn($validationContext);

        $context
            ->method('get')
            ->willReturn($validationContext);

        $validator = $this->createMock(Validatable::class);

        $processor = new Validate($validationContextFactory, $validator);

        $validator
            ->expects(self::once())
            ->method('assert')
            ->with(self::equalTo('test'));

        $processor->process($context, new UserValue('test'));
    }

    public function testProcess_PassedInvalidValue_CallsContextHasMethod(): void
    {
        $context = $this->createMock(ContextInterface::class);
        $validationContextFactory = $this->createMock(ValidationContextFactoryInterface::class);

        $validationContext = $this->createMock(ValidationContextInterface::class);

        $validationContextFactory
            ->method('create')
            ->willReturn($validationContext);

        $context
            ->method('get')
            ->willReturn($validationContext);

        $validationException = $this->createMock(ValidationException::class);

        $validator = $this->createMock(Validatable::class);
        $validator
            ->method('assert')
            ->with(self::equalTo('test'))
            ->willThrowException($validationException);

        $processor = new Validate($validationContextFactory, $validator);

        $context
            ->expects(self::once())
            ->method('has')
            ->with(self::equalTo(ValidationContextInterface::class))
            ->willReturn(true);

        $processor->process($context, new UserValue('test'));
    }

    public function testProcess_PassedInvalidValueAndValidationContextDoesNotExistsInContext_CallsContextSetMethod(): void
    {
        $context = $this->createMock(ContextInterface::class);
        $context
            ->method('has')
            ->with(self::equalTo(ValidationContextInterface::class))
            ->willReturn(false);

        $validationContextFactory = $this->createMock(ValidationContextFactoryInterface::class);
        $validationContext = $this->createMock(ValidationContextInterface::class);

        $validationContextFactory
            ->method('create')
            ->willReturn($validationContext);

        $context
            ->method('get')
            ->willReturn($validationContext);

        $validationException = $this->createMock(ValidationException::class);

        $validator = $this->createMock(Validatable::class);
        $validator
            ->method('assert')
            ->with(self::equalTo('test'))
            ->willThrowException($validationException);

        $processor = new Validate($validationContextFactory, $validator);

        $context
            ->expects(self::once())
            ->method('set')
            ->with(
                self::equalTo(ValidationContextInterface::class),
                self::equalTo($validationContext)
            );

        $processor->process($context, new UserValue('test'));
    }

    public function testProcess_PassedInvalidValueAndValidationContextExistsInContext_CallsValidationContextAddErrorMethod(): void
    {
        $context = $this->createMock(ContextInterface::class);
        $validationContextFactory = $this->createMock(ValidationContextFactoryInterface::class);
        $validationContext = $this->createMock(ValidationContextInterface::class);

        $context
            ->method('has')
            ->with(self::equalTo(ValidationContextInterface::class))
            ->willReturn(true);

        $context
            ->method('get')
            ->with(self::equalTo(ValidationContextInterface::class))
            ->willReturn($validationContext);

        $validator = $this->createMock(Validatable::class);
        $validationException = $this->createMock(ValidationException::class);

        $validator
            ->method('assert')
            ->with(self::equalTo('test'))
            ->willThrowException($validationException);

        $processor = new Validate($validationContextFactory, $validator);

        $validationContext
            ->expects(self::once())
            ->method('addError')
            ->with(self::equalTo($validationException));

        $processor->process($context, new UserValue('test'));
    }

    public function testProcess_PassedInvalidValueAndValidationContextExistsInContext_ReturnsValidationFailedValue(): void
    {
        $context = $this->createMock(ContextInterface::class);
        $validationContextFactory = $this->createMock(ValidationContextFactoryInterface::class);
        $validationContext = $this->createMock(ValidationContextInterface::class);

        $context
            ->method('has')
            ->with(self::equalTo(ValidationContextInterface::class))
            ->willReturn(true);

        $context
            ->method('get')
            ->with(self::equalTo(ValidationContextInterface::class))
            ->willReturn($validationContext);

        $validator = $this->createMock(Validatable::class);
        $validationException = $this->createMock(ValidationException::class);

        $validator
            ->method('assert')
            ->with(self::equalTo('test'))
            ->willThrowException($validationException);

        $processor = new Validate($validationContextFactory, $validator);

        self::assertEquals(
            new ValidationFailedValue($validationException),
            $processor->process($context, new UserValue('test'))
        );
    }

    public function testProcess_PassedValidValue_ReturnsInputValue(): void
    {
        $context = $this->createMock(ContextInterface::class);
        $validationContextFactory = $this->createMock(ValidationContextFactoryInterface::class);
        $validator = $this->createMock(Validatable::class);

        $processor = new Validate($validationContextFactory, $validator);
        $value = new UserValue('test');

        self::assertEquals($value, $processor->process($context, $value));
    }
}
