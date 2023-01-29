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
            'email' => 'some@email.com',
            'phone' => '+1-111-111-1111',
            'ip' => '127.0.0.256'
        ];

        $context = new Context();

        $result = a::marshalArray(
            $context,
            $source,
            a::toKey('email', a::pipe(
                a::get('[email]'),
                a::validate(v::email()),
            )),
            a::toKey('phone', a::pipe(
                a::get('[phone]'),
                a::validate(v::phone()),
            )),
            a::toKey('ip', a::pipe(
                a::get('[ip]'),
                a::validate(v::ip()->setName('IP Address')),
                a::ifValidationFailed(a::value('127.0.0.1'))
            )),
        );

        self::assertSame(
            [
                'email' => 'some@email.com',
                'phone' => '+1-111-111-1111',
                'ip' => '127.0.0.1',
            ],
            $result
        );

        self::assertTrue($context->has(ValidationContextInterface::class));
        self::assertTrue($context->get(ValidationContextInterface::class)->hasErrors());
        self::assertCount(1, $context->get(ValidationContextInterface::class)->getErrors());
        self::assertSame(
            ['IP Address' => 'IP Address must be an IP address'],
            $context->get(ValidationContextInterface::class)->getErrors()[0]->getMessages()
        );
    }
}
