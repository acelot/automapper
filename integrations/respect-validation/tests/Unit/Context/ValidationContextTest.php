<?php declare(strict_types=1);

namespace Acelot\AutoMapper\Integrations\RespectValidation\Tests\Unit\Context;

use Acelot\AutoMapper\Integrations\RespectValidation\Context\ValidationContext;
use PHPUnit\Framework\TestCase;
use Respect\Validation\Exceptions\ValidationException;

/**
 * @covers \Acelot\AutoMapper\Integrations\RespectValidation\Context\ValidationContext
 */
final class ValidationContextTest extends TestCase
{
    public function testAddError_PassedException_GetErrorsReturnsCorrectValue(): void
    {
        $context = new ValidationContext();

        $e = $this->createMock(ValidationException::class);
        $context->addError($e);

        self::assertSame($e, $context->getErrors()[0]);
    }

    public function testHasErrors_NoExceptionsAdded_ReturnsFalse(): void
    {
        $context = new ValidationContext();

        self::assertFalse($context->hasErrors());
    }

    public function testHasErrors_PassedException_ReturnsTrue(): void
    {
        $context = new ValidationContext();

        $e = $this->createMock(ValidationException::class);
        $context->addError($e);

        self::assertTrue($context->hasErrors());
    }

    public function testGetErrors_PassedExceptions_ReturnsCorrectValue(): void
    {
        $context = new ValidationContext();

        $e0 = $this->createMock(ValidationException::class);
        $context->addError($e0);

        $e1 = $this->createMock(ValidationException::class);
        $context->addError($e1);

        self::assertSame([$e0, $e1], $context->getErrors());
    }
}
