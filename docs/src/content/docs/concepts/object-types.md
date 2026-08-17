---
title: Object Types
description:
  "Points, bounds, geohashes, GeoJSON objects, and strings: and the
  longitude/latitude gotcha."
---

Tile38 stores five kinds of objects. Each maps to a builder in
`Ronappleton\Tile38PhpClient\Commands\Objects`.

## Point

A latitude/longitude, with an optional **Z** coordinate (elevation, timestamp,
…).

```php
use Ronappleton\Tile38PhpClient\Commands\Objects\Point;

Point::make(51.5007, -0.1246);        // POINT 51.5007 -0.1246
Point::make(51.5007, -0.1246, 10.0);  // POINT 51.5007 -0.1246 10
```

**The client's `Point` takes latitude, longitude** (Tile38's `POINT` order). For
`nearby` searches the third argument is the **search radius in meters**.

## Bounds

A minimum bounding rectangle: southwest latitude, southwest longitude, northeast
latitude, northeast longitude.

```php
use Ronappleton\Tile38PhpClient\Commands\Objects\Bounds;

Bounds::make(33.7840, -112.1520, 33.7848, -112.1512);
// BOUNDS 33.784 -112.152 33.7848 -112.1512
```

## GeoHash

A geohash string.

```php
use Ronappleton\Tile38PhpClient\Commands\Objects\GeoHash;

GeoHash::make('9tbnwg'); // HASH 9tbnwg
```

## GeoJSON (php-geojson)

Full GeoJSON objects are built with the
[php-geojson](https://github.com/ronappleton/php-geojson) package and wrapped
with `GeoJson`:

```php
use RonAppleton\GeoJson\Enums\GeoJsonType;
use RonAppleton\GeoJson\Objects\Factory;
use Ronappleton\Tile38PhpClient\Commands\Objects\GeoJson;

$point = Factory::make(GeoJsonType::Point);
$point->setPoints(-0.1246, 51.5007); // NOTE: GeoJSON is longitude, latitude

$client->set('cities', 'london', GeoJson::make($point))->execute();
```

Any GeoJSON type works: `Point`, `LineString`, `Polygon`, `MultiPolygon`,
`Feature`, `FeatureCollection`, and more:

```php
use RonAppleton\GeoJson\Enums\GeoJsonType;
use RonAppleton\GeoJson\Objects\Factory;

$lineString = Factory::make(GeoJsonType::LineString);

$a = Factory::make(GeoJsonType::Point);
$a->setPoints(-0.1, 51.5);

$b = Factory::make(GeoJsonType::Point);
$b->setPoints(-0.2, 51.6);

$lineString->addPoints($a, $b);

$client->set('routes', 'bus-12', GeoJson::make($lineString))->execute();
```

> **The longitude/latitude gotcha.** The client's `Point` is _latitude,
> longitude_ (Tile38's `POINT` keyword). php-geojson's `Point` is _longitude,
> latitude_ (the GeoJSON standard). Mixing them up puts you on the wrong side of
> the planet.

## String

Plain strings are stored with `StringValue` and searched with the `SEARCH`
command.

```php
use Ronappleton\Tile38PhpClient\Commands\Objects\StringValue;

$client->set('users', 'alice', StringValue::make('London'))->execute();
```

## Search areas

For `WITHIN` and `INTERSECTS`, the same namespace also provides `Circle`,
`Tile`, `QuadKey`, `Sector`, and `Roam` (for `NEARBY` roaming geofences).

```php
use Ronappleton\Tile38PhpClient\Commands\Objects\Circle;

$client->intersects('fleet', Circle::make(51.5, -0.12, 1000))->ids()->execute();
```

## Reference

| Builder               | Wire keyword | Arguments                            |
| --------------------- | ------------ | ------------------------------------ |
| `Point::make()`       | `POINT`      | latitude, longitude, `?z`            |
| `Bounds::make()`      | `BOUNDS`     | swLat, swLon, neLat, neLon           |
| `GeoHash::make()`     | `HASH`       | geohash                              |
| `GeoJson::make()`     | `OBJECT`     | a php-geojson object                 |
| `StringValue::make()` | `STRING`     | value                                |
| `Circle::make()`      | `CIRCLE`     | lat, lon, meters                     |
| `Tile::make()`        | `TILE`       | x, y, zoom                           |
| `QuadKey::make()`     | `QUADKEY`    | quadkey                              |
| `Sector::make()`      | `SECTOR`     | lat, lon, meters, bearing1, bearing2 |
| `Roam::make()`        | `ROAM`       | key, pattern, meters                 |
