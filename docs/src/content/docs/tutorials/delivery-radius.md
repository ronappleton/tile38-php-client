---
title: Delivery Radius
description:
  "Complex: find orders inside a delivery area, filtered by price and status,
  using WITHIN, fields, and GeoJSON."
---

**Level:** Complex

A courier has a delivery zone. You need to hand them every open order inside
that zone, weighted by price, in one query.

## The model

- **Orders** live in a `orders` key: each is a point with `price` and `status`
  fields.
- **Delivery zones** are GeoJSON polygons in a `zones` key.

## Seed an order

```php
use Ronappleton\Tile38PhpClient\Clients\Tile38;
use Ronappleton\Tile38PhpClient\Commands\Objects\Point;

$client = new Tile38('127.0.0.1', 9851);

$client->set('orders', 'ord-1001', Point::make(51.5012, -0.1252))
    ->field('price', 24.5)
    ->field('status', 'open')
    ->execute();
```

## Find orders inside a zone

`WITHIN` returns everything completely inside an area:

```php
$result = $client->within('orders', Bounds::make(51.3, -0.3, 51.7, 0.1))
    ->where('status', 'open', 'open')
    ->objects()
    ->execute();
```

## Filter, sort, and page

Combine every modifier:

```php
$result = $client->within('orders', Bounds::make(51.3, -0.3, 51.7, 0.1))
    ->where('status', 'open', 'open')
    ->where('price', 10, 100)
    ->limit(50)
    ->objects()
    ->execute();
```

## Intersect with an arbitrary GeoJSON zone

Zones aren't always rectangles. Store the zone as a GeoJSON polygon and use
`INTERSECTS` (overlaps) or `WITHIN` (fully inside):

```php
use RonAppleton\GeoJson\Enums\GeoJsonType;
use RonAppleton\GeoJson\Objects\Factory;
use Ronappleton\Tile38PhpClient\Commands\Objects\GeoJson;

$zone = Factory::make(GeoJsonType::Polygon);

// ...build the ring with boundary points...
// $zone->setExteriorRing($p1, $p2, $p3, $p4, $p1);

$client->set('zones', 'zone-north', GeoJson::make($zone))->execute();
```

Then query with the polygon as the area:

```php
$result = $client->intersects('orders', GeoJson::make($zone))
    ->where('status', 'open', 'open')
    ->ids()
    ->execute();
```

## Add a buffer

Slightly grow the search area so border cases aren't missed:

```php
$result = $client->within('orders', GeoJson::make($zone))
    ->buffer(500.0)          // extend the area by 500 m
    ->where('status', 'open', 'open')
    ->ids()
    ->execute();
```

> `BUFFER` is available on Tile38 1.27.0+: see
> [Version Compatibility](/reference/version-compatibility/).

## One query, real answer

```php
function ordersForZone(Tile38 $client, GeoJson $zone, float $buffer = 0.0): array
{
    $query = $client->within('orders', $zone)
        ->where('status', 'open', 'open')
        ->objects();

    if ($buffer > 0.0) {
        $query->buffer($buffer);
    }

    $response = json_decode($query->execute(), true);

    return $response['objects'] ?? [];
}
```

## What you learned

- `WITHIN` / `INTERSECTS` take any area: bounds, circles, or GeoJSON polygons.
- `where` filters combine as AND clauses.
- `->buffer()` grows a search area by meters.

**Next:** [Realtime Dispatch](/tutorials/driver-dispatch/): push matching offers
to drivers over webhooks.
