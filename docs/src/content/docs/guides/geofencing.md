---
title: Geofencing & Channels
description:
  Turn any search into a live geofence and publish events to pub/sub channels.
---

A geofence is a search that keeps watching. When an object enters, exits, or
crosses the search area, Tile38 fires an event. You receive those events over a
**pub/sub channel**.

## Create a channel

`setchan` builds a geofence from a search. You must call `->fence()`:

```php
use Ronappleton\Tile38PhpClient\Enums\SearchType;
use Ronappleton\Tile38PhpClient\Commands\Objects\Point;

$client->setchan('warehouse', SearchType::Nearby, 'fleet', Point::make(51.5, -0.12, 500))
    ->fence()
    ->execute();
```

The channel is named `warehouse`, watches the `fleet` collection, and fires
whenever a fleet object moves within 500 m of the point.

## Choose the search type

`NEARBY`, `WITHIN`, and `INTERSECTS` are supported: pass the matching
`SearchType`:

```php
use Ronappleton\Tile38PhpClient\Enums\SearchType;
use Ronappleton\Tile38PhpClient\Commands\Objects\Bounds;

$client->setchan('zones', SearchType::Within, 'fleet', Bounds::make(51.3, -0.3, 51.7, 0.1))
    ->fence()
    ->execute();
```

## Filter and shape events

```php
$client->setchan('fast-trucks', SearchType::Nearby, 'fleet', Point::make(51.5, -0.12, 5000))
    ->where('speed', 70, '+inf')          // only trucks going fast
    ->detect('enter,exit')                // only enter and exit events
    ->commands('set')                     // only react to SET commands
    ->fence()
    ->execute();
```

`->detect()` accepts `inside`, `outside`, `enter`, `exit`, and `cross`.

## List, update, delete

```php
$client->chans('*')->execute();        // list channels matching a pattern
$client->delchan('warehouse')->execute();   // delete one channel
$client->pdelchan('ware*')->execute();      // delete channels by pattern
```

Re-running `setchan` with the same name overwrites the channel.

## Subscribe

`SUBSCRIBE` and `PSUBSCRIBE` stream events on a connection. They are long-lived
and blocking, so they are best used from a dedicated connection:

```php
// A second client, dedicated to listening
$listener = new Tile38('127.0.0.1', 9851);

$listener->subscribe('warehouse')->execute();   // blocks, streaming events
```

## Metadata and expiry

Channels accept key/value metadata and an expiry:

```php
$client->setchan('warehouse', SearchType::Nearby, 'fleet', Point::make(51.5, -0.12, 500))
    ->meta('team', 'logistics')
    ->ex(3600)
    ->fence()
    ->execute();
```

## Tutorial

See [Geofence Alerts](/tutorials/geofence-alerts/) for a complete, runnable
example.
