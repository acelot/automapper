ℹ️ You are on a branch with the second version of the `acelot/automapper`.
If you want a previous version, then proceed to [1.x](https://github.com/acelot/automapper/tree/1.x) branch.

<p align="center">
<picture>
<source media="(prefers-color-scheme: dark)" srcset="./logo-night.png">
<img alt="AutoMapper" width="450" src="./logo-light.png">
</picture>
</p>

<p align="center">
<a href="https://github.com/acelot/automapper/actions"><img src="https://github.com/acelot/automapper/actions/workflows/pipeline.yml/badge.svg" alt="build"/></a>
<a href="./clover.xml"><img src="https://raw.githubusercontent.com/acelot/automapper/master/badge-coverage.svg" alt="coverage"/></a>
<a href="https://packagist.org/packages/acelot/automapper"><img src="https://img.shields.io/packagist/v/acelot/automapper.svg?style=flat" alt="packagist"/></a>
<img src="https://img.shields.io/badge/dependencies-zero-blue.svg?style=flat" alt="dependencies"/>
<img src="https://img.shields.io/github/license/acelot/automapper.svg?style=flat" alt="MIT"/>
</p>

**AutoMapper** is a powerful declarative data mapper for PHP 8.
AutoMapper can map data from any source data (usually array/object) to the existing array/object or marshal a new ones.

## Install

```bash
composer require acelot/automapper:^2.0
```

## Usage

### How to marshal new array from the another?

```php
use Acelot\AutoMapper\Context;
use Acelot\AutoMapper\AutoMapper as a;

$source = [
    'id' => '99',
    'name' => [
        'lastname' => 'Doe',
        'firstname' => 'John',
    ],
    'skills' => 'Php, CSS,JS,html, MySql,  brainfuck,'
];

$result = marshalArray(
    new Context(),
    $source,
    a::toKey('id', a::pipe(
        a::get('[id]'),
        a::toInt()
    )),
    a::toKey('fullname', a::pipe(
        a::get('[name]'),
        a::custom(fn($v) => $v['firstname'] . ' ' . $v('lastname'))
    )),
    a::toKey('skills', a::pipe(
        a::get('[skills]'),
        a::explodeString(','),
        a::mapIterable(a::pipe(
            a::trimString(),
            a::ifEmpty(ignore()),
            a::custom('strtolower')
        )),
        a::toArray(),
        a::sortArray()
    ))
);

// Output of `var_export($result)`
array(
  'id' => 99,
  'fullname' => 'John Doe',
  'skills' => [
    0 => 'brainfuck',
    1 => 'css',
    2 => 'html',
    3 => 'js',
    4 => 'mysql',
    5 => 'php',
  ],
)
```

### How to map data from source to the existing array?

<details><summary>Show the code</summary>

```php
use Acelot\AutoMapper\Context;
use Acelot\AutoMapper\AutoMapper as a;

$source = [
    'title' => '  Product title  ',
    'desc' => [
        'Product short description',
        'Product regular description',
        'Product descriptive description',
    ]
];

$target = [
    'id' => 5,
    'title' => 'Current title',
];

$result = a::map(
    new Context(),
    $source,
    $target,
    a::toKey('title', a::pipe(
        a::get('[title]'),
        a::trimString()
    )),
    a::toKey('description', a::get('[desc][#last]')),
);

// Output of `var_export($result)`
array (
  'id' => 5,
  'title' => 'Product title',
  'description' => 'Product descriptive description',
)
```

</details>

## Examples

All examples can be found in [`tests/Functional`](tests/Functional) directory.

## Reference

No need to use concrete classes, it's better to use the AutoMapper API [static functions](src/AutoMapper.php).
It is very convenient to import the AutoMapper as a short alias, for example `use Acelot\AutoMapper\AutoMapper as a`.

### Main functions

The main functions of AutoMapper.

| Function                                                  | Description                                                              |
|-----------------------------------------------------------|--------------------------------------------------------------------------|
| [`map`](tests/Functional/mapTest.php)                     | Maps data from the source to the target. Target is passing by reference. |
| [`marshalArray`](tests/Functional/marshalArrayTest.php)   | Maps data from the source to the keys of the new array.                  |
| [`marshalObject`](tests/Functional/marshalObjectTest.php) | Maps data from the source to the properties of the new `stdClass`.       |

### Field definitions

Definitions that helps you to shape the target structure.

| Function                                           | Description                                                                    |
|----------------------------------------------------|--------------------------------------------------------------------------------|
| [`toKey`](tests/Functional/marshalArrayTest.php)   | Sets/creates the value by key with given name in the target array.             |
| [`toProp`](tests/Functional/marshalObjectTest.php) | Sets/creates the value by property with given name in the target object.       |
| [`toMethod`](tests/Functional/toMethodTest.php)    | Calls a method with given name with value as an argument in the target object. |
| [`toSelf`](tests/Functional/toSelfTest.php)        | Assigns a value to the target.                                                 |

### Processors

Core value processors. The purpose of processors is to retrieve the value or mutate the incoming value and pass it to the next one.

| Function                                                              | Description                                                                                                                                                                                                |
|-----------------------------------------------------------------------|------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------|
| [`assertType`](tests/Functional/assertTypeTest.php)                   | Asserts the type of value. Throws `UnexpectedValueException` if assert is failed.                                                                                                                          |
| [`call`](tests/Functional/callTest.php)                               | Process the value by user defined function.                                                                                                                                                                |
| [`callCtx`](tests/Functional/callCtxTest.php)                         | Same as `call` but [context](#what-is-context) will be passed as a first argument.                                                                                                                         |
| [`condition`](tests/Functional/conditionTest.php)                     | Condition processor. If the user-defined function returns `true`, then the value will be passed to the first processor, otherwise to the second.                                                           |
| [`conditionCtx`](tests/Functional/conditionCtxTest.php)               | Same as `condition` but [context](#what-is-context) will be passed to the user-defined function.                                                                                                           |
| [`find`](tests/Functional/findTest.php)                               | Finds the element in iterable by the given predicate function.                                                                                                                                             |
| [`findCtx`](tests/Functional/findCtxTest.php)                         | Same as `find` but [context](#what-is-context) will be passed to the predicate function.                                                                                                                   |
| [`get`](tests/Functional/getTest.php)                                 | Most useful processor. Fetches the value from the source by given [path](#how-to-use-get-processor).                                                                                                       |
| [`getFromCtx`](tests/Functional/getFromCtxTest.php)                   | Fetches the value from the [context](#what-is-context).                                                                                                                                                    |
| [`ignore`](tests/Functional/ignoreTest.php)                           | Always returns the `IgnoreValue`. This value will be ignored by field definition, `mapArray` and `mapIterator`                                                                                             |
| [`mapIterable`](tests/Functional/mapIterableTest.php)                 | Iterates over elements of an iterable and applies the given sub-processor. ℹ️ Produces values by `yield` operator, so in order to retrieve them you should to iterate the result or call `toArray` helper. |
| [`marshalNestedArray`](tests/Functional/marshalNestedArrayTest.php)   | The same function as `mapArray` only in the form of a processor. Accepts the value from the previous processor as a source.                                                                                |
| [`marshalNestedObject`](tests/Functional/marshalNestedObjectTest.php) | Same as `marshalNestedArray` only produces object.                                                                                                                                                         |
| [`notFound`](tests/Functional/notFoundTest.php)                       | Always returns the `NotFoundValue`.                                                                                                                                                                        |
| [`pass`](tests/Functional/passTest.php)                               | This processor do nothing and just returns the value untouched.                                                                                                                                            |
| [`pipe`](tests/Functional/marshalNestedArrayTest.php)                 | Conveyor processor. Accepts multiple sub-processors and pass the value to the first sub-processor, then pass the result of the first to the second, then to the third and so on.                           |
| [`value`](tests/Functional/conditionTest.php)                         | Just returns the given value.                                                                                                                                                                              |

### Helpers

Helpers are the processors that built on top of the another processors. Some helpers are just a shorthands to the core processors with specific arguments, some of them are combination of the multiple processors.

| Function                                                  | Description                                                                                                                                                    |
|-----------------------------------------------------------|----------------------------------------------------------------------------------------------------------------------------------------------------------------|
| [`joinArray`](tests/Functional/joinArrayTest.php)         | Joins the incoming array using the given separator. Throws `UnexpectedValueException` if incoming value is not an array.                                       |
| [`sortArray`](tests/Functional/sortArrayTest.php)         | Sorts the incoming array using built-in `sort` function. Throws `UnexpectedValueException` if incoming value is not an array.                                  |
| [`uniqueArray`](tests/Functional/uniqueArrayTest.php)     | Returns only unique elements of the incoming array. Throws `UnexpectedValueException` if incoming value is not an array.                                       |
| [`ifNotFound`](tests/Functional/notFoundTest.php)         | Checks if the incoming value is `NotFoundValue` and passes the value to other processors depending on the result.                                              |
| [`ifEmpty`](tests/Functional/ifEmptyTest.php)             | Same as `ifNotFound` but checks if the value is `empty`.                                                                                                       |
| [`ifNull`](tests/Functional/ifNullTest.php)               | Same as `ifNotFound` but checks if the value `is_null`.                                                                                                        |
| [`IfEqual`](tests/Functional/ifEqualTest.php)             | Checks if the incoming value is equal to the given value.                                                                                                      |
| [`ifNotEqual`](tests/Functional/ifEqualTest.php)          | Checks if the incoming value is not equal to the given value.                                                                                                  |
| [`explodeString`](tests/Functional/explodeStringTest.php) | Splits the incoming string into parts using built-in `explode` function. Throws `UnexpectedValueException` if incoming value is not a string.                  |
| [`trimString`](tests/Functional/trimStringTest.php)       | Trims the incoming string using built-in `trim` function. Throws `UnexpectedValueException` if incoming value is not a string.                                 |
| [`toBool`](tests/Functional/toBoolTest.php)               | Converts the incoming value to boolean type.                                                                                                                   |
| [`toFloat`](tests/Functional/toFloatTest.php)             | Converts the incoming value to float type. Throws `UnexpectedValueException` if incoming value is not a scalar.                                                |
| [`toInt`](tests/Functional/toIntTest.php)                 | Converts the incoming value to integer type. Throws `UnexpectedValueException` if incoming value is not a scalar.                                              |
| [`toString`](tests/Functional/toStringTest.php)           | Converts the incoming value to string type. Throws `UnexpectedValueException` if incoming value is not a scalar or an object that not implements `__toString`. |
| [`toArray`](tests/Functional/toArrayTest.php)             | Converts the incoming value to array. Usually used with `mapIterable` processor.                                                                               |

## Integrations

| Name                                                   | Description                                                                                              |
|--------------------------------------------------------|----------------------------------------------------------------------------------------------------------|
| [`RespectValidation`](integrations/respect-validation) | Provides validation processor via [`respect/validation`](https://github.com/Respect/Validation) library. |

## FAQ

### What is Context?

The `Context` is a special DTO class for storing any kind of data: configs, DB connections, fixtures, etc.
This DTO is passed to the mapper, and you can use your data inside the processors.
Processors capable of working with the context end with `Ctx` suffix, [`callCtx`](tests/Functional/callCtxTest.php) for example.

### How to use `get` processor?

You can obtain any key/prop/method from the source using the [`get`](tests/Functional/getTest.php) processor which accepts a special path string.
The processor parses the given path and divides it into parts, then pulls out the data following the parts of the path.

Available path parts:

| Part                | Description                                          |
|---------------------|------------------------------------------------------|
| `@`                 | "Self Pointer" – returns the source itself           |
| `[0]`               | Returns an array value by index                      |
| `[key]`             | Returns an array value by key                        |
| `[some key]`        | Returns an array value by key with spaces            |
| `[#first]`          | Returns an array first value                         |
| `[#last]`           | Returns an array last value                          |
| `->property`        | Returns an object value by property                  |
| `->{some property}` | Returns an object value by property name with spaces |
| `->someMethod()`    | Calls an object method and returns the value         |

You can combine the parts to obtain the deep values:

```
[array_key][array key with spaces][#first][#last]->property->{property with spaces}->someMethod()
```

If any part of the path is not found, then the processor will return `NotFoundValue` value.
This value throws an `NotFoundException` but you can recover it using [`ifNotFound`](tests/Functional/notFoundTest.php) helper.
