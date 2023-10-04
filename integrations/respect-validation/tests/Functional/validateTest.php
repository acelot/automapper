<?php declare(strict_types=1);

namespace Acelot\AutoMapper\Integrations\RespectValidation\Tests\Functional;

use Acelot\AutoMapper\AutoMapper as a;
use Acelot\AutoMapper\Context\Context;
use Acelot\AutoMapper\Integrations\RespectValidation\ValidationContextInterface;
use Respect\Validation\Validator as v;
use PHPUnit\Framework\TestCase;

final class validateTest extends TestCase
{
    public function testFunction(): void
    {
        $source = [
            'id' => 10,
            'email' => 'some@@email.com',
            'phone' => '000+1-111-111-1111',
            'ip' => '127.0.0.256'
        ];

        $context = new Context();

        $result = a::marshalArray(
            $context,
            $source,
            a::toKey('email', a::pipe(
                a::get('[email]'),
                a::validate(v::email()->setName('E-mail')),
                a::ifValidationFailed(a::value('default@example.com')),
            )),
            a::toKey('phone', a::pipe(
                a::get('[phone]'),
                a::validate(v::phone()->setName('Phone')),
                a::ifValidationFailed(a::ignore())
            )),
            a::toKey('ip', a::pipe(
                a::get('[ip]'),
                a::validate(v::ip()->setName('IP Address')),
                a::ifValidationFailed(a::value('127.0.0.1'))
            )),
        );

        self::assertSame(
            [
                'email' => 'default@example.com',
                'ip' => '127.0.0.1',
            ],
            $result
        );

        $validationContext = $context->get(ValidationContextInterface::class);

        self::assertTrue($context->has(ValidationContextInterface::class));
        self::assertTrue($validationContext->hasErrors());
        self::assertCount(3, $validationContext->getErrors());
        self::assertSame(
            [
                'E-mail' => 'E-mail must be valid email',
                'Phone' => 'Phone must be a valid telephone number',
                'IP Address' => 'IP Address must be an IP address',
            ],
            [
                ...$validationContext->getErrors()[0]->getMessages(),
                ...$validationContext->getErrors()[1]->getMessages(),
                ...$validationContext->getErrors()[2]->getMessages(),
            ]
        );
    }
}
