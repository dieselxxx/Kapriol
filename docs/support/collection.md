---
layout: default
title: Collection
parent: Support
nav_order: 1
---
# Collection

- [# Introduction](#-introduction)
- [# Basic collection](#-basic-collection)
- - [# Creating basic array collection](#-creating-basic-array-collection)
- - [# Passing array to collection](#-passing-array-to-collection)
- - [# Reading from basic collection](#-reading-from-basic-collection)

## # Introduction

Collection is a wrapper for creating and managing list of data like arrays, objects, files etc.

FireHub offers lots of different collection types you can work with. Some of them are more focused
on speed,  some on memory consumption and some are specialized to handle special data types like
objects, files etc.

Once you instantiate `Collection` class you will be presented with a couple of static method that
represents different collection types.

All collections currently in FireHub are considered to be _lazy_, means that function in `Collection`
static method will not fill collection entities until you actually need them or ask for them.
Our collections in examples bellow won't produce any results until we ask for collection items or try to do
some other function on top of our initial function.

## # Basic collection
Array collection is a basic collection type collection that has main focus of performance
and doesn't concern itself about memory consumption.
This collection can hold any type of data.

### # Creating basic array collection

Basic collection, or sometime called array collection can be instantiated when calling `create` static
method.
`create` method accepts only one argument, anonymous or arrow function.

Thing to remember is that anonymous function requested by the `create` method must always return array.

Let's try to create basic collection from list of numbers.


```php
use FireHub\Support\Collections\Collection;

$collection = Collection::create(function ():array {
    for($i = 0; $i < 1_000_000; $i++) {
        $list[$i] = $i++;
    }
    return $list ?? [];
});
```

### # Passing array to collection

If you already have an array that you just want to pass to collection and use of the collection features
on it, you can do it like on the example bellow.

```php
use FireHub\Support\Collections\Collection;

$example_array = [
'firstname' => 'John',
'lastname' => 'Doe',
'age' => 25
];

// anonymous style collection
$collection = Collection::create(function () use ($example_array):array {
    return $example_array;
});

// arrow function style collection
$collection = Collection::create(fn ():array => $example_array);
```

### # Reading from basic collection

All collections currently in FireHub are considered to be _lazy_, means that function in create method
will not fill collection entities until you actually need them or ask for them.
Our collection in example won't produce any results until we as for collection items or try to do
some other function on top of our initial function.

Let's try to create basic collection from list of numbers and var_dump results.

```php
use FireHub\Support\Collections\Collection;

$collection = Collection::create(function ():array {
    for($i = 0; $i < 1_000_000; $i++) {
        $list[$i] = $i++;
    }
    return $list ?? [];
});

// read underlying array represented of the collection
$collection->all();

// result
array (size=1000000)
  0 => int 0
  1 => int 1
  2 => int 2
  3 => int 3
  more elements...
```
