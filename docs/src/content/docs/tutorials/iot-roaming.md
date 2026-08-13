---
title: IoT Roaming Geofences
description: "Complex: geofences that follow a moving object, with ROAM and live channels."
---

**Level:** Complex

A tracked pet, a rental car, a delivery drone. You don't just want a fixed zone: you want a **roaming geofence** that moves with the object and reports when anything
else comes near it.

## What a roaming geofence is

A normal `NEARBY` geofence is anchored to one point. A **ROAM** geofence is anchored
to another object: the fence follows that object's current position, and fires when
other objects come within `meters` of it.

## Set the anchor

A `cars` collection holds the moving anchors:

```php
use Ronappleton\Tile38PhpClient\Clients\Tile38;
use Ronappleton\Tile38PhpClient\Commands\Objects\Point;

$client = new Tile38('127.0.0.1', 9851);

// The rental car is the roaming anchor.
$client->set('cars', 'car-7', Point::make(51.5012, -0.1252))->execute();

// A tracker key has the things that might approach it.
$client->set('trackers', 'child-a', Point::make(51.5020, -0.1260))->execute();
```

## Build the roaming fence

`NEARBY` with a `Roam` area watches the `cars` key and reacts to objects in the
`trackers` key within 100 m of the car:

```php
use Ronappleton\Tile38PhpClient\Commands\Objects\Roam;
use Ronappleton\Tile38PhpClient\Enums\SearchType;

$client->setchan('car-7-zone', SearchType::Nearby, 'trackers', Roam::make('cars', 'car-7', 100.0))
    ->detect('enter,exit')
    ->fence()
    ->execute();
```

`Roam::make(key, pattern, meters)`: the pattern selects the anchor by id.

## The fence follows the car

As `car-7` moves, the 100 m fence moves with it. Move the car:

```php
$client->set('cars', 'car-7', Point::make(51.5100, -0.1320))->execute();
```

Now `trackers` objects within 100 m of the *new* position fire events.

## React to tracker movement

Update a tracker and watch it cross the boundary:

```php
$client->set('trackers', 'child-a', Point::make(51.5110, -0.1330))->execute();
```

If `child-a` just entered the car's 100 m zone, an `enter` event is published on the
`car-7-zone` channel.

## Webhooks for roaming

The same works with webhooks when the events should reach your API:

```php
$client->sethook(
    'car-7-zone',
    'https://api.example.com/hooks/car-7',
    SearchType::Nearby,
    'trackers',
    Roam::make('cars', 'car-7', 100.0),
)->fence()->execute();
```

## What you learned

- `Roam::make(key, pattern, meters)` creates a roaming search area.
- The geofence re-anchors to the object automatically as it moves.
- Roaming fences work with both channels and webhooks.

**Next:** [Real Estate Search](/tutorials/real-estate/): search property polygons with filters.
