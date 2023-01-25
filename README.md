ℹ️ You are on a branch with the second version of the `acelot/automapper`. If you want a previous version, then proceed to [1.x](https://github.com/acelot/automapper/tree/1.x) branch.

⚠️ **WORK IN PROGRESS**

<img src=https://user-images.githubusercontent.com/1065215/214675170-3a1411b7-12d0-4cd1-b666-fc2cec4bf8b9.png width="450"/>

[![build](https://github.com/acelot/automapper/actions/workflows/matrix-test.yml/badge.svg)](https://github.com/acelot/automapper/actions)
![сoverage](https://raw.githubusercontent.com/acelot/automapper/v2/badge-coverage.svg)
[![packagist](https://img.shields.io/packagist/v/acelot/automapper.svg?style=flat)](https://packagist.org/packages/acelot/automapper)
![deps](https://img.shields.io/badge/dependencies-zero-blue.svg?style=flat)
![license](https://img.shields.io/github/license/acelot/automapper.svg?style=flat)

Powerful declarative data mapper for PHP 8. AutoMapper can map data from any source data (usually array/object) to the existing array/object or marshal a new ones.

## Install

```bash
composer require acelot/automapper:^2.0
```

## Usage

### API

The main functions of AutoMapper.

| Function        | Description                                                              |
|-----------------|--------------------------------------------------------------------------|
| `map`           | Maps data from the source to the target. Target is passing by reference. |
| `marshalArray`  | Maps data from the source to keys of the new array.                      |
| `marshalObject` | Maps data from the source to properties of the new `stdClass`.           |

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

| Function              | Description                                                                                                                                                                                                  |
|-----------------------|--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------|
| `call`                | Process the value by user defined function.                                                                                                                                                                  |
| `callCtx`             | Same as `call` but context will be passed as a first argument.                                                                                                                                               |
| `condition`           | Condition processor. If the user-defined function returns `true`, then the value will be passed to the first processor, otherwise to the second.                                                             |
| `conditionCtx`        | Same as `condition` but context will be passed to the user-defined function.                                                                                                                                 |
| `get`                 | Most useful processor. Fetches the value from the source by given path.                                                                                                                                      |
| `getFromCtx`          | Fetches the value from the context.                                                                                                                                                                          |
| `ignore`              | Always returns the `IgnoreValue`. This value will be ignored by field definition, `mapArray` and `mapIterator`                                                                                               |
| `mapArray`            | Works like PHP built-in `array_map` function. Processor iterates over elements of an array and applies giver sub-processor.                                                                                  |
| `mapIterator`         | Same as `mapArray` but accepts objects that implements `Iterator` interface. ℹ️ Produces values by `yield` operator, so in order to retrieve them you should to iterate the result or call `toArray` helper. |
| `marshalNestedArray`  | The same function as `mapArray` only in the form of a processor. Accepts the value from the previous processor as a source.                                                                                  |
| `marshalNestedObject` | Same as `marshalNestedArray` only produces object.                                                                                                                                                           |
| `notFound`            | Always returns the `NotFoundValue`. This value throws an `NotFoundException` but you can recover it using `ifNotFound` helper.                                                                               |
| `pass`                | This processor do nothing and just returns the value untouched.                                                                                                                                              |
| `pipe`                | Conveyor processor. Accepts multiple sub-processors and pass the value to the first sub-processor, then pass the result of the first to the second, then to the third and so on.                             |
| `value`               | Just returns the given value.                                                                                                                                                                                |

### Helpers

Helpers are the processors that built on top of the another processors. Some helpers are just a shorthands to the core processors with specific arguments, some of them are combinations of multiple processors.



## Examples

Examples can be found in [examples](https://github.com/acelot/automapper/tree/v2/src) directory.
