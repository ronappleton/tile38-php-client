---
title: Search
description: "SCAN, SEARCH, NEARBY, WITHIN, and INTERSECTS: plus every shared option."
---

Tile38 provides five search commands. All of them share the same modifier methods
(`where`, `limit`, `match`, …) and the same output formats.

## Commands

### SCAN: iterate a collection

```php
$client->scan('fleet')
    ->where('speed', 70, '+inf')
    ->match('truck*')
    ->ids()
    ->execute();
```

### SEARCH: string values

`SEARCH` only finds `STRING` objects:

```php
$client->search('users')
    ->match('J*')
    ->desc()
    ->ids()
    ->execute();
```

### NEARBY: nearest to a point

Requires a `Point` (with the radius in meters as the third argument):

```php
$client->nearby('fleet', Point::make(51.5074, -0.1278, 5000))
    ->distance()
    ->points()
    ->execute();
```

### WITHIN: fully inside an area

Requires an area (`Bounds`, `Circle`, `GeoJson`, …):

```php
$client->within('fleet', Bounds::make(51.3, -0.3, 51.7, 0.1))
    ->ids()
    ->execute();
```

### INTERSECTS: overlaps an area

```php
$client->intersects('parcels', GeoJson::make($polygon))
    ->ids()
    ->execute();
```

## Shared modifiers

These builder methods are available on all five commands:

| Method                       | Wire keyword | Notes                                   |
|------------------------------|--------------|-----------------------------------------|
| `->cursor(int)`              | `CURSOR`     | iterate large result sets               |
| `->limit(int)`               | `LIMIT`      | default 100                             |
| `->match(string)`            | `MATCH`      | glob on the object id; repeatable       |
| `->where(field, min, max)`   | `WHERE`      | filter on a field; repeatable (AND)     |
| `->wherein(field, values[])` | `WHEREIN`    | field is one of the listed values       |
| `->whereeval(script, args)`  | `WHEREEVAL`  | Lua filter                              |
| `->whereevalsha(sha, args)`  | `WHEREEVALSHA` | Lua filter by cached script           |
| `->nofields()`               | `NOFIELDS`   | omit fields from results                |
| `->asc()` / `->desc()`       | `ASC`/`DESC` | ordering (SCAN / SEARCH)                |
| `->distance()`               | `DISTANCE`   | NEARBY: include distance in results     |
| `->buffer(float)`            | `BUFFER`     | WITHIN / INTERSECTS: grow the area      |
| `->clip()`                   | `CLIP`       | INTERSECTS: clip results to the area    |
| `->clipby(area)`             | `CLIPBY`     | clip to BOUNDS/HASH/TILE/QUADKEY        |
| `->fence()`                  | `FENCE`      | turn the search into a geofence         |
| `->detect(string)`           | `DETECT`     | geofence event types                    |
| `->commands(string)`         | `COMMANDS`   | geofence command mask                   |

> **Output formats must come last.** Tile38 parses output formats after the modifiers.
> The client always emits modifiers first and output formats last, so you can call
> them in any order:

```php
$client->nearby('fleet', Point::make(51.5, -0.12, 5000))
    ->points()          // output
    ->where('speed', 70, '+inf')   // modifier: still emitted before POINTS
    ->execute();
```

## Filtering

`where` filters on object **fields**:

```php
$client->scan('fleet')
    ->where('speed', 70, '+inf')
    ->where('age', '-inf', 24)
    ->ids()
    ->execute();
```

`+inf` and `-inf` mean unbounded. On Tile38 1.30.0+ you can use expression strings
and filter on GeoJSON properties too:

```php
$client->scan('fleet')
    ->where('properties.speed', 70, '+inf')
    ->ids()
    ->execute();
```

## Geofencing

Any search can become a live geofence with `->fence()`. See
[Geofencing & Channels](/guides/geofencing/).
