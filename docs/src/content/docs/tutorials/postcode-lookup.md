---
title: Postcode & Geocoding Lookup
description: "Simple: store postcodes with their coordinates and resolve them in both directions at microsecond speed."
---

**Level:** Simple

You need fast postcode → coordinates and coordinates → postcode lookups, at scale.
Tile38 is an in-memory spatial store, which makes both directions instant.

## Load a postcode dataset

A postcode is just an id with a point. UK postcodes are published openly
(e.g. [ONS](https://geoportal.statistics.gov.uk/) / free sources); here is the shape:

```
SW1A1AA,51.5007,-0.1246
SW1A2AA,51.5035,-0.1287
SW1A3AA,51.5060,-0.1300
```

Load them with `SET`: an id per postcode, a point for its centroid:

```php
use Ronappleton\Tile38PhpClient\Clients\Tile38;
use Ronappleton\Tile38PhpClient\Commands\Objects\Point;

$client = new Tile38('127.0.0.1', 9851);

foreach ($rows as [$postcode, $lat, $lon]) {
    $client->set('postcodes', $postcode, Point::make((float) $lat, (float) $lon))->execute();
}
```

For large imports, batch the writes: Tile38 pipelines them efficiently. A full
country dataset (about 1.7M postcodes for the UK) fits comfortably in memory.

## Postcode → coordinates

`GET` by postcode is a single in-memory lookup:

```php
$result = $client->get('postcodes', 'SW1A1AA')->point()->execute();
// {"ok":true,"point":[51.5007,-0.1246],...}
```

## Coordinates → postcode

Reverse geocoding is a `NEARBY` search for the nearest match. A tight radius plus
`limit(1)` finds the closest centroid:

```php
$result = $client->nearby('postcodes', Point::make(51.5012, -0.1252, 1000))
    ->distance()
    ->ids()
    ->execute();

// {"ok":true,"ids":["SW1A1AA"],"distance":[...],...}
```

## Drop the point once found

For a lookup you usually only care about the nearest id: use `->ids()` and read it
directly:

```php
$response = json_decode(
    $client->nearby('postcodes', Point::make(51.5012, -0.1252, 500))
        ->ids()
        ->execute(),
    true,
);

$postcode = $response['ids'][0] ?? null;
```

## Handle gaps gracefully

If a coordinate is in open water or un-postcoded land, the search returns nothing:

```php
$response = json_decode(
    $client->nearby('postcodes', Point::make(50.5, -10.0, 500))->ids()->execute(),
    true,
);

if (empty($response['ids'])) {
    // no postcode within 500m: respond with a "not found"
}
```

## Boundary-accurate lookups

Centroids are fast but approximate. If you need the exact containing postcode,
store postcode boundary polygons as GeoJSON and use `WITHIN` instead:

```php
use RonAppleton\GeoJson\Enums\GeoJsonType;
use RonAppleton\GeoJson\Objects\Factory;
use Ronappleton\Tile38PhpClient\Commands\Objects\GeoJson;

// $polygon is a php-geojson Polygon built from the boundary coordinates.
$client->set('boundaries', 'SW1A1AA', GeoJson::make($polygon))->execute();

$result = $client->within('boundaries', Point::make(51.5012, -0.1252, 0))
    ->ids()
    ->execute();
```

> `WITHIN` needs an area. Pass a `Point` as the area to test containment in the
> smallest sense: see the [Search](/concepts/search/) docs for the exact shapes.

## What you learned

- Postcode → coordinates is a plain `GET`.
- Coordinates → postcode is a `NEARBY` with a small radius and `limit(1)`.
- Boundary polygons make lookups exact, at the cost of more data.

**Next:** [Delivery Radius](/tutorials/delivery-radius/): a more complex use of the same primitives.
