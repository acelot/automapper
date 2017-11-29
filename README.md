# AutoMapper

[![travis](https://img.shields.io/travis/acelot/automapper/master.svg?style=flat)](https://travis-ci.org/acelot/automapper)
[![packagist](https://img.shields.io/packagist/v/acelot/automapper.svg?style=flat)](https://packagist.org/packages/acelot/automapper)
![deps](https://img.shields.io/badge/dependencies-zero-blue.svg?style=flat)
![license](https://img.shields.io/github/license/acelot/automapper.svg?style=flat)

Simple declarative data mapper for PHP 7.

## Install

```bash
composer require acelot/automapper
```

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