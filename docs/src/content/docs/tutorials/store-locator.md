---
title: Store Locator
description:
  "Simple: find the nearest branch, filtered by opening hours and type."
---

**Level:** Simple

You have a chain of coffee shops. Visitors pick a location and you show the
nearest three branches that are open and sell takeaway.

## Seed the stores

Each branch is a point with fields:

```php
use Ronappleton\Tile38PhpClient\Clients\Tile38;
use Ronappleton\Tile38PhpClient\Commands\Objects\Point;

$client = new Tile38('127.0.0.1', 9851);

$stores = [
    's-101' => [51.5074, -0.1278, 'open', '1'],
    's-102' => [51.5100, -0.1350, 'closed', '1'],
    's-103' => [51.5030, -0.1150, 'open', '0'],
    's-104' => [51.5150, -0.1450, 'open', '1'],
];

foreach ($stores as $id => [$lat, $lon, $open, $takeaway]) {
    $client->set('stores', $id, Point::make($lat, $lon))
        ->field('open', $open)
        ->field('takeaway', $takeaway)
        ->execute();
}
```

## Nearest open branches with takeaway

```php
$result = $client->nearby('stores', Point::make(51.5060, -0.1300, 5000))
    ->where('open', 'open', 'open')
    ->where('takeaway', 1, 1)
    ->limit(3)
    ->distance()
    ->ids()
    ->execute();
```

> `WHERE` compares against numeric fields, so store `open` as `1`/`0` for range
> filters. The example above uses string equality: pick one convention and stick
> to it.

A cleaner version treats both fields as numbers:

```php
$result = $client->nearby('stores', Point::make(51.5060, -0.1300, 5000))
    ->where('open', 1, 1)
    ->where('takeaway', 1, 1)
    ->limit(3)
    ->distance()
    ->ids()
    ->execute();
```

## Return full objects instead of ids

Swap `->ids()` for `->objects()` and hand the result straight to the front end:

```php
$result = $client->nearby('stores', Point::make(51.5060, -0.1300, 5000))
    ->where('open', 1, 1)
    ->where('takeaway', 1, 1)
    ->limit(3)
    ->objects()
    ->execute();
```

## What you learned

- Combine `nearby` with `where` filters and `limit` for "top N matching"
  queries.
- Store filterable attributes as numeric fields.
- `->objects()` returns ready-to-render GeoJSON.

**Next:** [Asset Check-In](/tutorials/asset-checkin/): expiry-based data with
`EX` and `TTL`.
