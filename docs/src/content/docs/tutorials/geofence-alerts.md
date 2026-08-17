---
title: Geofence Alerts
description:
  'Simple: get notified when an object enters or leaves an area, without
  polling.'
---

**Level:** Simple

Instead of polling "is anything inside the zone?", let Tile38 push you an event
the moment something enters or exits.

## Create the geofence

A geofenced channel watches a search and publishes events:

```php
use Ronappleton\Tile38PhpClient\Clients\Tile38;
use Ronappleton\Tile38PhpClient\Commands\Objects\Point;
use Ronappleton\Tile38PhpClient\Enums\SearchType;

$client = new Tile38('127.0.0.1', 9851);

$client->setchan('warehouse', SearchType::Nearby, 'fleet', Point::make(51.5, -0.12, 500))
    ->detect('enter,exit')
    ->fence()
    ->execute();
```

Now every `fleet` object that enters or exits a 500 m radius of the warehouse
fires an event on the `warehouse` channel.

## Listen

`SUBSCRIBE` streams events on the connection. It blocks, so use a dedicated
client:

```php
$listener = new Tile38('127.0.0.1', 9851);
$listener->subscribe('warehouse')->execute();
```

Each event looks roughly like:

```json
{
  "ok": true,
  "command": "set",
  "detect": "enter",
  "id": "truck-12",
  "object": { "type": "Point", "coordinates": [-0.118, 51.502] },
  "key": "fleet"
}
```

## Filter before it reaches you

Keep the noise down at the source:

```php
$client->setchan('fast-entries', SearchType::Nearby, 'fleet', Point::make(51.5, -0.12, 500))
    ->where('speed', 70, '+inf')   // only objects with speed field > 70
    ->detect('enter')              // only enter events
    ->fence()
    ->execute();
```

## Trigger a set to see it work

```php
use Ronappleton\Tile38PhpClient\Commands\Objects\Point;

$client->set('fleet', 'truck-12', Point::make(51.501, -0.121))
    ->field('speed', 80)
    ->execute();
```

Truck-12 is inside the 500 m radius and moving faster than 70: an `enter` event
is published.

## Clean up

```php
$client->delchan('warehouse')->execute();
$client->delchan('fast-entries')->execute();
```

## What you learned

- `setchan` + `->fence()` turns a search into a live geofence.
- `SearchType` picks the search shape (`Nearby`, `Within`, `Intersects`).
- `->detect()` and `->where()` filter events server-side.

**Next:** [Realtime Dispatch](/tutorials/driver-dispatch/): push geofence events
to your own API with webhooks.
