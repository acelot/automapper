ℹ️ You are on a branch with the second version of the `acelot/automapper`. If you want a previous version, then proceed to [1.x](https://github.com/acelot/automapper/tree/1.x) branch.

⚠️ **WORK IN PROGRESS**

<p align="center">
<picture>
<source media="(prefers-color-scheme: dark)" srcset="./logo-night.png">
<img alt="AutoMapper" width="450" src="./logo-light.png">
</picture>
</p>

<p align="center">
<a href="https://github.com/acelot/automapper/actions"><img src="https://github.com/acelot/automapper/actions/workflows/pipeline.yml/badge.svg" alt="build"/></a>
<a href="https://github.com/acelot/automapper/tree/v2/clover.xml"><img src="https://raw.githubusercontent.com/acelot/automapper/v2/badge-coverage.svg" alt="coverage"/></a>
<a href="https://packagist.org/packages/acelot/automapper"><img src="https://img.shields.io/packagist/v/acelot/automapper.svg?style=flat" alt="packagist"/></a>
<img src="https://img.shields.io/badge/dependencies-zero-blue.svg?style=flat" alt="dependencies"/>
<img src="https://img.shields.io/github/license/acelot/automapper.svg?style=flat" alt="MIT"/>
</p>

**AutoMapper** – powerful declarative data mapper for PHP 8. AutoMapper can map data from any source data (usually array/object) to the existing array/object or marshal a new ones.

## Install

```bash
composer require acelot/automapper:^2.0
```

## Usage

### How to marshal new array from the another?

```php
use Acelot\AutoMapper\Context;
use function Acelot\AutoMapper\{
    custom,
    explodeString,
    get,
    ifEmpty,
    ignore,
    mapIterable,
    marshalArray,
    pipe,
    sortArray,
    toArray,
    toKey,
    trimString
};

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
    toKey('id', pipe(
        get('[id]'),
        toInt()
    )),
    toKey('fullname', pipe(
        get('[name]'),
        custom(fn($v) => $v['firstname'] . ' ' . $v('lastname'))
    )),
    toKey('skills', pipe(
        get('[skills]'),
        explodeString(','),
        mapIterable(pipe(
            trimString(),
            ifEmpty(ignore()),
            sortArray(),
            custom('strtolower')
        )),
        toArray()
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
use function Acelot\AutoMapper\{
    get,
    map,
    pipe,
    toKey,
    trimString
};

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

$result = map(
    new Context(),
    $source,
    $target,
    toKey('title', pipe(
        get('[title]'),
        trimString()
    )),
    toKey('description', get('[desc][#last]')),
);

// Output of `var_export($result)`
array (
  'id' => 5,
  'title' => 'Product title',
  'description' => 'Product descriptive description',
)
```

</details>

### What is Context?

The `Context` is a special DTO class for storing any kind of data: configs, DB connections, fixtures, etc.
This DTO is passed to the mapper, and you can use your data inside the processors. Processors able to use Context are ending with `Ctx` suffix.

## Reference

No need to use concrete classes, it's better to use the API [functions](src/functions.php). The library API is contained in functions.php file.

### Main functions

The main functions of AutoMapper.

| Function                                            | Description                                                              |
|-----------------------------------------------------|--------------------------------------------------------------------------|
| [`map`](tests/Examples/mapTest.php)                 | Maps data from the source to the target. Target is passing by reference. |
| [`marshalArray`](tests/Examples/marshalArray.php)   | Maps data from the source to the keys of the new array.                  |
| [`marshalObject`](tests/Examples/marshalObject.php) | Maps data from the source to the properties of the new `stdClass`.       |

### Field definitions

Definitions that helps you to shape the target structure.

| Function   | Description                                                                    |
|------------|--------------------------------------------------------------------------------|
| `toKey`    | Sets/creates the value by key with given name in the target array.             |
| `toProp`   | Sets/creates the value by property with given name in the target object.       |
| `toMethod` | Calls a method with given name with value as an argument in the target object. |
| `toSelf`   | Assigns a value to the target.                                                 |

### Processors

Core value processors. The purpose of processors is to retrieve the value or mutate the incoming value and pass it to the next one.

| Function              | Description                                                                                                                                                                                                |
|-----------------------|------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------|
| `call`                | Process the value by user defined function.                                                                                                                                                                |
| `callCtx`             | Same as `call` but context will be passed as a first argument.                                                                                                                                             |
| `condition`           | Condition processor. If the user-defined function returns `true`, then the value will be passed to the first processor, otherwise to the second.                                                           |
| `conditionCtx`        | Same as `condition` but context will be passed to the user-defined function.                                                                                                                               |
| `find`                | Finds the element in iterable by the given predicate function.                                                                                                                                             |
| `findCtx`             | Same as `find` but context will be passed to the predicate function.                                                                                                                                       |
| `get`                 | Most useful processor. Fetches the value from the source by given path.                                                                                                                                    |
| `getFromCtx`          | Fetches the value from the context.                                                                                                                                                                        |
| `ignore`              | Always returns the `IgnoreValue`. This value will be ignored by field definition, `mapArray` and `mapIterator`                                                                                             |
| `mapIterable`         | Iterates over elements of an iterable and applies the given sub-processor. ℹ️ Produces values by `yield` operator, so in order to retrieve them you should to iterate the result or call `toArray` helper. |
| `marshalNestedArray`  | The same function as `mapArray` only in the form of a processor. Accepts the value from the previous processor as a source.                                                                                |
| `marshalNestedObject` | Same as `marshalNestedArray` only produces object.                                                                                                                                                         |
| `notFound`            | Always returns the `NotFoundValue`. This value throws an `NotFoundException` but you can recover it using `ifNotFound` helper.                                                                             |
| `pass`                | This processor do nothing and just returns the value untouched.                                                                                                                                            |
| `pipe`                | Conveyor processor. Accepts multiple sub-processors and pass the value to the first sub-processor, then pass the result of the first to the second, then to the third and so on.                           |
| `value`               | Just returns the given value.                                                                                                                                                                              |

### Helpers

Helpers are the processors that built on top of the another processors. Some helpers are just a shorthands to the core processors with specific arguments, some of them are combination of the multiple processors.

| Function        | Description                                                                                                                                                    |
|-----------------|----------------------------------------------------------------------------------------------------------------------------------------------------------------|
| `joinArray`     | Joins the incoming array using the given separator. Throws `UnexpectedValueException` if incoming value is not an array.                                       |
| `sortArray`     | Sorts the incoming array using built-in `sort` function. Throws `UnexpectedValueException` if incoming value is not an array.                                  |
| `uniqueArray`   | Returns only unique elements of the incoming array. Throws `UnexpectedValueException` if incoming value is not an array.                                       |
| `ifNotFound`    | Checks if the incoming value is `NotFoundValue` and passes the value to other processors depending on the result.                                              |
| `ifEmpty`       | Same as `ifNotFound` but checks if the value is `empty`.                                                                                                       |
| `ifNull`        | Same as `ifNotFound` but checks if the value `is_null`.                                                                                                        |
| `ifLt`          | Checks if the incoming value is less than the given value.                                                                                                     |
| `ifLte`         | Checks if the incoming value is less than the given value or equal.                                                                                            |
| `IfEqual`       | Checks if the incoming value is equal with the given value.                                                                                                    |
| `ifNotEqual`    | Checks if the incoming value is not equal with the given value.                                                                                                |
| `ifGte`         | Checks if the incoming value is greater than the given value or equal.                                                                                         |
| `ifGt`          | Checks if the incoming value is greater than the given value.                                                                                                  |
| `explodeString` | Splits the incoming string into parts using built-in `explode` function. Throws `UnexpectedValueException` if incoming value is not a string.                  |
| `trimString`    | Trims the incoming string using built-in `trim` function. Throws `UnexpectedValueException` if incoming value is not a string.                                 |
| `toBool`        | Converts the incoming value to boolean type.                                                                                                                   |
| `toFloat`       | Converts the incoming value to float type. Throws `UnexpectedValueException` if incoming value is not a scalar.                                                |
| `toInt`         | Converts the incoming value to integer type. Throws `UnexpectedValueException` if incoming value is not a scalar.                                              |
| `toString`      | Converts the incoming value to string type. Throws `UnexpectedValueException` if incoming value is not a scalar or an object that not implements `__toString`. |
| `toArray`       | Converts the incoming value to array. Usually used with `mapIterable` processor.                                                                               |

## Examples

Examples can be found in [examples](tests/Examples) directory.
