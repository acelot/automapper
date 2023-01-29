<?php declare(strict_types=1);

namespace Acelot\AutoMapper\Integrations\RespectValidation\Tests\Unit\Context;

use Acelot\AutoMapper\Integrations\RespectValidation\Context\ValidationContext;
use Acelot\AutoMapper\Integrations\RespectValidation\Context\ValidationContextFactory;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Acelot\AutoMapper\Integrations\RespectValidation\Context\ValidationContextFactory
 */
final class ValidationContextFactoryTest extends TestCase
{
    public function testCreate_Constructed_ReturnsValidationContext(): void
    {
        $factory = new ValidationContextFactory();

        self::assertInstanceOf(ValidationContext::class, $factory->create());
    }
}
