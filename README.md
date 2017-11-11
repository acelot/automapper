# AutoMapper

[![Build Status](https://travis-ci.org/acelot/automapper.svg?branch=master)](https://travis-ci.org/acelot/automapper)
![](https://img.shields.io/badge/dependencies-zero-blue.svg)
![](https://img.shields.io/badge/license-MIT-green.svg)

Simple declarative data mapper for PHP 7.

## Usage

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
    }))
)->ignoreAllMissing();

// Source data
$source = [
    'id' => '100',
    'text' => 'Very useful text. ',
    'is_active' => 0
];

// Target
$target = $mapper->marshalObject($source);
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