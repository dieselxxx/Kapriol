---
layout: default
title: Collection
parent: Support
nav_order: 1
---
# Collection

- [# Introduction](#-introduction)
- [# Array collection](#-array-collection)
- - [# Creating array collection](#-creating-array-collection)

## # Introduction

Collection is a wrapper for creating and managing list of data like arrays, objects, files etc.

FireHub offers lots of different collection types you can work with. Some of them are more focused
on speed,  some on memory consumption and some are specialized to handle special data types like
objects, files etc.

## # Array collection
Array collection is a basic collection type is collection that has main focus of performance
and doesn't concern itself about memory consumption.
This collection can hold any type of data.

### # Creating array collection
Bellow is an example how to create basic collection from list of numbers.

```php
use FireHub\Support\Collections\Collection;

$create = Collection::create(function ():array {
    for($i = 0; $i < 1000000; $i++) {
        $list[$i] = $i++;
    }
    return $list ?? [];
});
```

Bellow is an example how to create a collection from something more complex, like reading
large log file line by line and turning it as a collection.

```php
use FireHub\Support\Collections\Collection;

$create = Collection::create(function ():array {
    $handle = fopen('log.log', 'r');
    while (($line = fgets($handle)) !== false) {
        $lines[] = $line;
    }
    return $lines ?? [];
});
```

### # Passing array to collection

If you already have an array that you just want to pass to collection you can do it like on the
example bellow.

```php
use FireHub\Support\Collections\Collection;

$example_array = [
'firstname' => 'John',
'lastname' => 'Doe',
'age' => 25
];

$create = Collection::create(function () use (array $example_array):array {
    return $array;
});
```
