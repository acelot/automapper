<?php declare(strict_types=1);

namespace Acelot\AutoMapper\Integrations\RespectValidation\Processor;

use Acelot\AutoMapper\ContextInterface;
use Acelot\AutoMapper\Integrations\RespectValidation\ValidationContextFactoryInterface;
use Acelot\AutoMapper\Integrations\RespectValidation\ValidationContextInterface;
use Acelot\AutoMapper\Integrations\RespectValidation\Value\ValidationFailedValue;
use Acelot\AutoMapper\ProcessorInterface;
use Acelot\AutoMapper\Value\UserValue;
use Acelot\AutoMapper\ValueInterface;
use Respect\Validation\Exceptions\ValidationException;
use Respect\Validation\Validatable;

final class Validate implements ProcessorInterface
{
    public function __construct(
        private ValidationContextFactoryInterface $validationContextFactory,
        private Validatable $validator
    ) {}

    public function getValidator(): Validatable
    {
        return $this->validator;
    }

    public function process(ContextInterface $context, ValueInterface $value): ValueInterface
    {
        if (!$value instanceof UserValue) {
            return $value;
        }

        try {
            $this->validator->assert($value->getValue());
        } catch (ValidationException $e) {
            $this->getValidationContext($context)->addError($e);

            return new ValidationFailedValue($e);
        }

        return $value;
    }

    private function getValidationContext(ContextInterface $context): ValidationContextInterface
    {
        if (!$context->has(ValidationContextInterface::class)) {
            $context->set(ValidationContextInterface::class, $this->validationContextFactory->create());
        }

        return $context->get(ValidationContextInterface::class);
    }
}
