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

| Function                                      | Description                                                                    |
|-----------------------------------------------|--------------------------------------------------------------------------------|
| [`toKey`](tests/Examples/marshalArray.php)    | Sets/creates the value by key with given name in the target array.             |
| [`toProp`](tests/Examples/marshalObject.php)  | Sets/creates the value by property with given name in the target object.       |
| [`toMethod`](tests/Examples/toMethodTest.php) | Calls a method with given name with value as an argument in the target object. |
| [`toSelf`](tests/Examples/toSelfTest.php)     | Assigns a value to the target.                                                 |

### Processors

Core value processors. The purpose of processors is to retrieve the value or mutate the incoming value and pass it to the next one.

| Function                                                            | Description                                                                                                                                                                                                |
|---------------------------------------------------------------------|------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------|
| [`call`](tests/Examples/callTest.php)                               | Process the value by user defined function.                                                                                                                                                                |
| [`callCtx`](tests/Examples/callCtxTest.php)                         | Same as `call` but context will be passed as a first argument.                                                                                                                                             |
| [`condition`](tests/Examples/conditionTest.php)                     | Condition processor. If the user-defined function returns `true`, then the value will be passed to the first processor, otherwise to the second.                                                           |
| [`conditionCtx`](tests/Examples/conditionCtxTest.php)               | Same as `condition` but context will be passed to the user-defined function.                                                                                                                               |
| [`find`](tests/Examples/findTest.php)                               | Finds the element in iterable by the given predicate function.                                                                                                                                             |
| [`findCtx`](tests/Examples/findCtxTest.php)                         | Same as `find` but context will be passed to the predicate function.                                                                                                                                       |
| [`get`](tests/Examples/getTest.php)                                 | Most useful processor. Fetches the value from the source by given path.                                                                                                                                    |
| [`getFromCtx`](tests/Examples/getFromCtxTest.php)                   | Fetches the value from the context.                                                                                                                                                                        |
| [`ignore`](tests/Examples/ignoreTest.php)                           | Always returns the `IgnoreValue`. This value will be ignored by field definition, `mapArray` and `mapIterator`                                                                                             |
| [`mapIterable`](tests/Examples/mapIterableTest.php)                 | Iterates over elements of an iterable and applies the given sub-processor. ℹ️ Produces values by `yield` operator, so in order to retrieve them you should to iterate the result or call `toArray` helper. |
| [`marshalNestedArray`](tests/Examples/marshalNestedArrayTest.php)   | The same function as `mapArray` only in the form of a processor. Accepts the value from the previous processor as a source.                                                                                |
| [`marshalNestedObject`](tests/Examples/marshalNestedObjectTest.php) | Same as `marshalNestedArray` only produces object.                                                                                                                                                         |
| [`notFound`](tests/Examples/notFoundTest.php)                       | Always returns the `NotFoundValue`. This value throws an `NotFoundException` but you can recover it using `ifNotFound` helper.                                                                             |
| [`pass`](tests/Examples/passTest.php)                               | This processor do nothing and just returns the value untouched.                                                                                                                                            |
| [`pipe`](tests/Examples/pipeTest.php)                               | Conveyor processor. Accepts multiple sub-processors and pass the value to the first sub-processor, then pass the result of the first to the second, then to the third and so on.                           |
| [`value`](tests/Examples/valueTest.php)                             | Just returns the given value.                                                                                                                                                                              |

### Helpers

Helpers are the processors that built on top of the another processors. Some helpers are just a shorthands to the core processors with specific arguments, some of them are combination of the multiple processors.

| Function                                                | Description                                                                                                                                                    |
|---------------------------------------------------------|----------------------------------------------------------------------------------------------------------------------------------------------------------------|
| [`joinArray`](tests/Examples/joinArrayTest.php)         | Joins the incoming array using the given separator. Throws `UnexpectedValueException` if incoming value is not an array.                                       |
| [`sortArray`](tests/Examples/sortArrayTest.php)         | Sorts the incoming array using built-in `sort` function. Throws `UnexpectedValueException` if incoming value is not an array.                                  |
| [`uniqueArray`](tests/Examples/uniqueArrayTest.php)     | Returns only unique elements of the incoming array. Throws `UnexpectedValueException` if incoming value is not an array.                                       |
| [`ifNotFound`](tests/Examples/ifNotFoundTest.php)       | Checks if the incoming value is `NotFoundValue` and passes the value to other processors depending on the result.                                              |
| [`ifEmpty`](tests/Examples/ifEmptyTest.php)             | Same as `ifNotFound` but checks if the value is `empty`.                                                                                                       |
| [`ifNull`](tests/Examples/ifNullTest.php)               | Same as `ifNotFound` but checks if the value `is_null`.                                                                                                        |
| [`ifLt`](tests/Examples/ifLtTest.php)                   | Checks if the incoming value is less than the given value.                                                                                                     |
| [`ifLte`](tests/Examples/ifLteTest.php)                 | Checks if the incoming value is less than the given value or equal.                                                                                            |
| [`IfEqual`](tests/Examples/IfEqualTest.php)             | Checks if the incoming value is equal to the given value.                                                                                                      |
| [`ifNotEqual`](tests/Examples/ifNotEqualTest.php)       | Checks if the incoming value is not equal to the given value.                                                                                                  |
| [`ifGte`](tests/Examples/ifGteTest.php)                 | Checks if the incoming value is greater than the given value or equal.                                                                                         |
| [`ifGt`](tests/Examples/ifGtTest.php)                   | Checks if the incoming value is greater than the given value.                                                                                                  |
| [`explodeString`](tests/Examples/explodeStringTest.php) | Splits the incoming string into parts using built-in `explode` function. Throws `UnexpectedValueException` if incoming value is not a string.                  |
| [`trimString`](tests/Examples/trimStringTest.php)       | Trims the incoming string using built-in `trim` function. Throws `UnexpectedValueException` if incoming value is not a string.                                 |
| [`toBool`](tests/Examples/toBoolTest.php)               | Converts the incoming value to boolean type.                                                                                                                   |
| [`toFloat`](tests/Examples/toFloatTest.php)             | Converts the incoming value to float type. Throws `UnexpectedValueException` if incoming value is not a scalar.                                                |
| [`toInt`](tests/Examples/toIntTest.php)                 | Converts the incoming value to integer type. Throws `UnexpectedValueException` if incoming value is not a scalar.                                              |
| [`toString`](tests/Examples/toStringTest.php)           | Converts the incoming value to string type. Throws `UnexpectedValueException` if incoming value is not a scalar or an object that not implements `__toString`. |
| [`toArray`](tests/Examples/toArrayTest.php)             | Converts the incoming value to array. Usually used with `mapIterable` processor.                                                                               |

## Examples

Examples can be found in [examples](tests/Examples) directory.
