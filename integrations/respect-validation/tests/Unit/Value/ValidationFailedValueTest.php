<?php declare(strict_types=1);

namespace Acelot\AutoMapper\Integrations\RespectValidation\Tests\Unit\Value;

use Acelot\AutoMapper\Integrations\RespectValidation\Value\ValidationFailedValue;
use PHPUnit\Framework\TestCase;
use Respect\Validation\Exceptions\ValidationException;

/**
 * @covers \Acelot\AutoMapper\Integrations\RespectValidation\Value\ValidationFailedValue
 */
final class ValidationFailedValueTest extends TestCase
{
    public function testGetException_Constructed_ReturnsPassedException(): void
    {
        $e = $this->createMock(ValidationException::class);
        $value = new ValidationFailedValue($e);

        self::assertSame($e, $value->getException());
    }
}
