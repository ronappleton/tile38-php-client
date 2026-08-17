---
title: Track a Fleet
description:
  'A simple walkthrough: store vehicle positions and run nearby queries in
  realtime.'
---

**Level:** Simple

You have a fleet of delivery vehicles reporting their position. You want to
store every position and answer one question instantly: _which vehicles are near
me right now?_

## Set up

```php
use Ronappleton\Tile38PhpClient\Clients\Tile38;
use Ronappleton\Tile38PhpClient\Commands\Objects\Point;

$client = new Tile38('127.0.0.1', 9851);
```

## Store a position

Whenever a vehicle reports in, overwrite its point:

```php
// Each report updates the truck's position.
$client->set('fleet', $vehicleId, Point::make($latitude, $longitude))
    ->field('speed', $speed)
    ->execute();
```

The id is the vehicle id, so a new report simply overwrites the old one.

## "Who is near me?"

Pass a search radius in meters as the third argument to `Point::make`:

```php
$result = $client->nearby('fleet', Point::make(51.5074, -0.1278, 5000))
    ->distance()
    ->ids()
    ->execute();

// {"ok":true,"ids":["truck-12","van-03"],"count":2,...}
```

`->distance()` includes the distance from the search point in the response.

## Add a filter

Only vehicles moving faster than 40:

```php
$client->nearby('fleet', Point::make(51.5074, -0.1278, 5000))
    ->where('speed', 40, '+inf')
    ->ids()
    ->execute();
```

## The whole loop

```php
// On each vehicle ping...
function reportPosition(Tile38 $client, string $id, float $lat, float $lon, float $speed): void
{
    $client->set('fleet', $id, Point::make($lat, $lon))
        ->field('speed', $speed)
        ->execute();
}

// ...and to answer "near me" from a mobile app...
function vehiclesNear(Tile38 $client, float $lat, float $lon, float $meters): array
{
    $result = $client->nearby('fleet', Point::make($lat, $lon, $meters))
        ->ids()
        ->execute();

    return json_decode($result, true)['ids'] ?? [];
}
```

## What you learned

- `SET` with an id overwrites, so the latest report wins.
- `NEARBY` is a nearest-neighbour search: the third `Point` argument is the
  radius in meters.
- `->where()` filters results by field values server-side.

**Next:** [Geofence Alerts](/tutorials/geofence-alerts/): stop polling, let
Tile38 tell you when things move.
