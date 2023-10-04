# Respect Validation Integration for AutoMapper

To use this integration you should install the [`respect/validation`](https://packagist.org/packages/respect/validation) composer package first.

## Usage

### How to validate source data?

`\Respect\Validation\Exceptions\ValidationException` will be thrown on error.

```php
use Acelot\AutoMapper\Context;
use Acelot\AutoMapper\Integrations\RespectValidation\Context\ValidationContext;
use Acelot\AutoMapper\AutoMapper as a;
use Respect\Validation\Validator as v;

$source = [
    'email' => 'press@google.com',
    'phone' => '1-650-253-0000'
];

$result = marshalArray(
    new Context(),
    $source,
    a::toKey('email', a::pipe(
        a::get('[email]'),
        a::validate(v::email())
    ))
    a::toKey('phone', a::pipe(
        a::get('[phone]'),
        a::validate(v::phone())
    )),
);

// Output of `var_export($result)`
array(
  'email' => 'press@google.com',
  'phone' => '1-650-253-0000',
)
```

### How to recover a validation exception?

```php
use Acelot\AutoMapper\Context;
use Acelot\AutoMapper\AutoMapper as a;
use Acelot\AutoMapper\Integrations\RespectValidation\ValidationContextInterface;
use Respect\Validation\Validator as v;

$source = [
    'email' => 'bademail@',
    'phone' => '1-650-253-0000'
];

$context = new Context();

$result = marshalArray(
    $context,
    $source,
    a::toKey('email', a::pipe(
        a::get('[email]'),
        a::validate(v::email()),
        a::ifValidationFailed(a::ignore())
    )),
    a::toKey('phone', a::pipe(
        a::get('[phone]'),
        a::validate(v::phone()),
        a::ifValidationFailed(a::ignore())
    ))
);

// Output of `var_export($result)`
array(
  'phone' => '1-650-253-0000',
)

// Validation errors can be found in Context
$validationErrors = $context->get(ValidationContextInterface::class)->getErrors();
```

### Processors

| Function                                        | Description                                                                                                                 |
|-------------------------------------------------|-----------------------------------------------------------------------------------------------------------------------------|
| [`validate`](tests/Functional/validateTest.php) | Validates the value and pass it if it's valid or returns the [`ValidationFailedValue`](src/Value/ValidationFailedValue.php) |

### Helpers

| Function                                                    | Description                                                                                                               |
|-------------------------------------------------------------|---------------------------------------------------------------------------------------------------------------------------|
| [`ifValidationFailed`](tests/Functional/validateTest.php)   | Checks if the incoming value is `ValidationFailedValue` and passes the value to other processors depending on the result. |
