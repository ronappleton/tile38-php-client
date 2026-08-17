---
title: Real Estate Search
description:
  "Complex: search property boundaries as GeoJSON, filter by price and bedrooms,
  and run point-in-boundary lookups."
---

**Level:** Complex

An estate agent lists properties as GeoJSON polygons (their footprint or plot).
Buyers search by area, price, and bedrooms. You also need "which property is
this coordinate inside?" for reverse lookups.

## Seed properties

Properties are polygons built with php-geojson. A simplified house footprint:

```php
use Ronappleton\Tile38PhpClient\Clients\Tile38;
use RonAppleton\GeoJson\Enums\GeoJsonType;
use RonAppleton\GeoJson\Objects\Factory;
use Ronappleton\Tile38PhpClient\Commands\Objects\GeoJson;

$client = new Tile38('127.0.0.1', 9851);

function polygonFromRing(array $ring): \RonAppleton\GeoJson\Interfaces\GeoJsonObject
{
    $polygon = Factory::make(GeoJsonType::Polygon);
    $points = array_map(
        static fn (array $c) => (Factory::make(GeoJsonType::Point))->setPoints($c[0], $c[1]),
        $ring,
    );
    $polygon->setExteriorRing(...$points, $points[0]);

    return $polygon;
}

$client->set('properties', 'p-1001', GeoJson::make(
    polygonFromRing([[-0.14, 51.50], [-0.13, 51.50], [-0.13, 51.51], [-0.14, 51.51]]),
))
    ->field('price', 425000)
    ->field('beds', 3)
    ->execute();
```

## Search within a rectangle

Buyers drew a box on the map:

```php
use Ronappleton\Tile38PhpClient\Commands\Objects\Bounds;

$result = $client->intersects('properties', Bounds::make(51.49, -0.15, 51.52, -0.12))
    ->where('price', 300000, 600000)
    ->where('beds', 2, 4)
    ->objects()
    ->execute();
```

`INTERSECTS` returns properties that overlap the box: including a
boundary-clipping footprint, which is what you want here.

## Tighten to "fully inside"

Use `WITHIN` when the buyer's area must fully contain the property:

```php
$result = $client->within('properties', Bounds::make(51.49, -0.15, 51.52, -0.12))
    ->where('beds', 3, 3)
    ->ids()
    ->execute();
```

## "Which property is this coordinate in?"

Reverse lookup with a point area:

```php
$result = $client->within('properties', Point::make(51.505, -0.135, 0))
    ->ids()
    ->execute();
```

The radius of `0` meters keeps the search effectively point-based.

## Add "nearby similar"

Blend the primitives: nearest properties, filtered, ordered by distance:

```php
use Ronappleton\Tile38PhpClient\Commands\Objects\Point;

$result = $client->nearby('properties', Point::make(51.505, -0.135, 2000))
    ->where('price', 300000, 600000)
    ->where('beds', 2, 4)
    ->distance()
    ->limit(5)
    ->objects()
    ->execute();
```

## Paginate results

Large areas need cursors:

```php
$first = json_decode(
    $client->within('properties', Bounds::make(51.49, -0.15, 51.52, -0.12))
        ->cursor(0)
        ->limit(20)
        ->ids()
        ->execute(),
    true,
);

$nextCursor = $first['cursor'] ?? 0;
// ...pass $nextCursor to the next call to continue.
```

## What you learned

- GeoJSON polygons stored with `GeoJson::make()` enable real spatial boundaries.
- `INTERSECTS` vs `WITHIN` chooses overlap vs containment semantics.
- Fields + `where` filter any query; `cursor` pages large results.

## Where to go next

All the primitives you used are documented in
[Object Types](/concepts/object-types/), [Search](/concepts/search/), and the
[Command Reference](/reference/commands/).
