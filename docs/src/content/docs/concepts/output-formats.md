---
title: Output Formats
description: "COUNT, IDS, OBJECTS, POINTS, BOUNDS, and HASHES: and the GET output selectors."
---

Search commands let you choose what the server returns.

## Search output formats

| Method              | Wire keyword | Returns                                  |
|---------------------|--------------|------------------------------------------|
| `->count()`         | `COUNT`      | total object count                       |
| `->ids()`           | `IDS`        | the object ids                           |
| `->objects()`       | `OBJECTS`    | full GeoJSON objects                     |
| `->points()`        | `POINTS`     | latitude/longitude pairs                 |
| `->bounds()`        | `BOUNDS`     | minimum bounding rectangles              |
| `->hashes(int)`     | `HASHES`     | geohashes at the given precision         |

```php
$client->scan('fleet')->count()->execute();
$client->scan('fleet')->ids()->execute();
$client->scan('fleet')->points()->execute();
$client->scan('fleet')->hashes(22)->execute();
```

The output format is emitted after all search modifiers (see
[Search](/concepts/search/)), so calling order does not matter.

## GET output selectors

`GET` returns the full object by default, but you can ask for a specific projection:

```php
use Ronappleton\Tile38PhpClient\Commands\Objects\Point;

$client->set('fleet', 'truck1', Point::make(51.5, -0.12))->execute();

$client->get('fleet', 'truck1')->point()->execute();      // GET ... POINT
$client->get('fleet', 'truck1')->bounds()->execute();     // GET ... BOUNDS
$client->get('fleet', 'truck1')->object()->execute();     // GET ... OBJECT
$client->get('fleet', 'truck1')->hashes(22)->execute();   // GET ... HASH 22
```

| Method              | Wire keyword | Returns                       |
|---------------------|--------------|-------------------------------|
| `->point()`         | `POINT`      | latitude/longitude            |
| `->bounds()`        | `BOUNDS`     | bounding box                  |
| `->object()`        | `OBJECT`     | full GeoJSON object           |
| `->hashes(int)`     | `HASH`       | geohash at the given precision |

## Fields

By default objects are returned without their fields. Use `withfields()` to include
them:

```php
$response = $client->get('fleet', 'truck1')->withfields()->execute();
// {"ok":true,"object":{...},"fields":{"speed":90},...}
```

`withfields()` is also available on `GET`, and `NOFIELDS` on search commands does the
reverse (drops fields from results).
