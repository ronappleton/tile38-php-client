---
title: Quick Start
description: Connect to Tile38, set your first point, and run a nearby search.
---

Every call follows the same shape: **the client hands you a command, you chain options,
then `execute()` sends it**: see [The Fluent API](/concepts/fluent-api/).

## 1. Connect

```php
use Ronappleton\Tile38PhpClient\Clients\Tile38;

$client = new Tile38('127.0.0.1', 9851);
```

## 2. Set an object

Store a truck at a point. `Point::make` takes **latitude, longitude** (Tile38's
`POINT` order):

```php
use Ronappleton\Tile38PhpClient\Commands\Objects\Point;

$client->set('fleet', 'truck1', Point::make(51.5007, -0.1246))->execute();
```

## 3. Get it back

```php
$result = $client->get('fleet', 'truck1')->execute();
// {"ok":true,"object":{"type":"Point","coordinates":[-0.1246,51.5007]},...}
```

Note the coordinates come back in **GeoJSON order: longitude, latitude**.

## 4. Search nearby

Find everything within 5 km of a coordinate. The third argument to `Point::make` is
the search radius **in meters** for `nearby`:

```php
$result = $client->nearby('fleet', Point::make(51.5074, -0.1278, 5000))
    ->limit(10)
    ->distance()
    ->points()
    ->execute();

// {"ok":true,"points":[...],"count":1,"cursor":0,...}
```

## 5. Add a field and filter on it

```php
$client->set('fleet', 'truck1', Point::make(51.5007, -0.1246))
    ->field('speed', 90)
    ->execute();

$result = $client->nearby('fleet', Point::make(51.5074, -0.1278, 5000))
    ->where('speed', 70, '+inf')
    ->ids()
    ->execute();
// {"ok":true,"ids":["truck1"],...}
```

## What's next

- [Configuration](/getting-started/configuration/): auth, timeouts, output format.
- [Object Types](/concepts/object-types/): points, bounds, geohashes, and GeoJSON.
- [Search](/concepts/search/): every search command and its options.
