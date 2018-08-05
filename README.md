# AutoMapper

[![travis](https://img.shields.io/travis/acelot/automapper/master.svg?style=flat)](https://travis-ci.org/acelot/automapper)
[![Code Climate](https://img.shields.io/codeclimate/coverage/acelot/automapper.svg)](https://codeclimate.com/github/acelot/automapper)
[![packagist](https://img.shields.io/packagist/v/acelot/automapper.svg?style=flat)](https://packagist.org/packages/acelot/automapper)
![deps](https://img.shields.io/badge/dependencies-zero-blue.svg?style=flat)
![license](https://img.shields.io/github/license/acelot/automapper.svg?style=flat)

Simple declarative data mapper for PHP 7.

AutoMapper can map data from array/object to existing array/object or marshal new ones. Mapping rules specified in declarative way using three simple definitions:
- **From** definition (`From::create` or via short function `from`) — maps single field from source to target. Supports chainable functions:
  - `->convert(callable $callable)` — converts input value to another one via any callable;
  - `->trim()` — trims value to eliminate whitespaces (suitable for strings);
  - `->default($defaultValue)` — returns default value if source field is missing;
  - `->ignoreMissing()` — ignores target field if source field is missing;
  - `->ignoreEmpty()`  — ignores target field if source field is empty.
- **Aggregate** definition (`Aggregate::create` or via short function `aggregate`) — maps multiple fields from source to single target field. Supports chainable functions:
  - `->trim()` — trims aggregated value
  - `->ignoreEmpty()`  — ignores target field if aggregated value is empty.
- **Ignore** definition (`Ignore::create` or via short function `ignore`) — simply ignores the field.
- **Value** definition (`Value::create` or via short function `value`) — maps constant value to target field. Supports chainable functions:
  - `->trim()`
  - `->ignoreEmpty()`

All missing source fields can be ignored via `AutoMapper::create(...)->ignoreAllMissing()` modifier.

## Install

```bash
composer require acelot/automapper
```

## Example

```php
use function Acelot\AutoMapper\{
    field, from, value, aggregate
}

// Define mapper
$mapper = AutoMapper::create(
    field('id', from('id')->convert('intval')),
    field('title', from('text')->trim()),
    field('url', from('link')->trim()),
    field('isActive', from('is_active')->convert(function ($value) {
        return $value === 1;
    })->default(false)),
    field('count', value(100)),
    field('isEmpty', aggregate(function (SourceInterface $source) {
        return !empty($source->get('title')) && !empty($source->get('url'));
    })),
    field('ignoreThisField', ignore())
)->ignoreAllMissing();

// Source data
$source = [
    'id' => '100',
    'text' => 'Very useful text. ',
    'is_active' => 0
];

// Target
$target = $mapper->marshalArray($source);
var_dump($target);
```

Output:
```
array(5) {
  ["id"]=>
  int(100)
  ["title"]=>
  string(17) "Very useful text."
  ["isActive"]=>
  bool(false)
  ["count"]=>
  int(100)
  ["isEmpty"]=>
  bool(false)
}
```
