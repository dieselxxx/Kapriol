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

Bellow is an example how to create a collection from something more complex, like reading large file line by line and turning it as a collection.

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
