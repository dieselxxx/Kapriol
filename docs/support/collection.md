---
layout: default
title: Collection
parent: Support
nav_order: 1
---
# Collection

- [# Introduction](#-introduction)
- [# Creating collection](#-creating-collection)
- - [# Creating array collection](#-creating-array-collection)

## # Introduction
Collection is a wrapper for creating and managing list of data like arrays, objects, files etc.

## # Creating collection

### # Creating array collection

```php
use FireHub\Support\Collections\Collection;

$create = Collection::create(function ():array {
    $xxx = [];
    $handle = fopen('log.log', 'r');
    while (($line = fgets($handle)) !== false) {
        $xxx[] = $line;
    }
    return $xxx;
});
```
