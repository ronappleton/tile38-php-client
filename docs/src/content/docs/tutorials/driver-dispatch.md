---
title: Realtime Dispatch
description: "Complex: match drivers to jobs with a geofence webhook, Lua filtering, and a Redis fan-out."
---

**Level:** Complex

When a new job is posted, you want the nearest available drivers to be offered it immediately, without polling. Tile38's geofence webhooks do the pushing; a little Lua
does the matching.

## The flow

1. A job is written to a `jobs` collection (a point with fields).
2. A geofence webhook watches `jobs` for `set` events near the dispatch hub.
3. On each event, a Lua script finds the nearest available drivers and offers them the job.
4. The offer is POSTed to your API for the final decision.

## Seed drivers

```php
use Ronappleton\Tile38PhpClient\Clients\Tile38;
use Ronappleton\Tile38PhpClient\Commands\Objects\Point;

$client = new Tile38('127.0.0.1', 9851);

$client->set('drivers', 'd-001', Point::make(51.5012, -0.1252))
    ->field('available', 1)
    ->execute();
$client->set('drivers', 'd-002', Point::make(51.5120, -0.1380))
    ->field('available', 1)
    ->execute();
```

## Post a job

```php
$client->set('jobs', 'job-9001', Point::make(51.5074, -0.1278))
    ->field('fare', 18.0)
    ->execute();
```

## The dispatch webhook

A `NEARBY` geofence on `jobs` fires on every `set`:

```php
use Ronappleton\Tile38PhpClient\Enums\SearchType;

$client->sethook(
    'dispatch',
    'https://api.example.com/hooks/dispatch',
    SearchType::Nearby,
    'jobs',
    Point::make(51.5074, -0.1278, 5000),
)
    ->commands('set')
    ->detect('inside')
    ->fence()
    ->execute();
```

Every time a job is set within 5 km of the hub, your endpoint receives a POST with
the job id and coordinates.

## Match drivers in Lua

In your endpoint handler, find available drivers near the job: server-side, in one
round trip:

```php
// In the webhook handler:
$jobId = $payload['id'];

$script = <<<'LUA'
  local drivers = tile38.call('NEARBY', KEYS[1], 'LIMIT', ARGV[1],
      'WHERE', 'available', 1, 1, 'POINT', ARGV[2], ARGV[3], ARGV[4], 'IDS')
  return drivers
LUA;

$result = $client->eval($script, 1, 'drivers', 3, 51.5074, -0.1278, 5000)->execute();
// nearest 3 available driver ids, closest first
```

The script uses `tile38.call()` to run a `NEARBY` search from inside Tile38, so no
extra network hop is involved.

## Cache the script

Loading once and calling by SHA1 keeps repeated dispatches cheap:

```php
$load = $client->script('load', $script)->execute();
$sha = $load['result'];

$result = $client->evalsha($sha, 1, 'drivers', 3, 51.5074, -0.1278, 5000)->execute();
```

## Give the job a timeout

Dispatching should never hang: wrap the matching in a timeout:

```php
$result = $client->eval($script, 1, 'drivers', 3, 51.5074, -0.1278, 5000)
    ->timeout(0.5)
    ->execute();
```

## The endpoint handler shape

```php
function handleDispatchEvent(string $rawBody, Tile38 $client): void
{
    $event = json_decode($rawBody, true);
    $jobId = $event['id'] ?? null;
    if ($jobId === null) {
        return;
    }

    // 1. Pull the job's coordinates from the event payload.
    $coords = $event['object']['coordinates'] ?? null;

    // 2. Find the 3 nearest available drivers (Lua, as above).
    // 3. POST the offer to each driver's device.
}
```

## What you learned

- `SETHOOK` + `->fence()` turns a search into a live, endpoint-pushed feed.
- `->commands('set')` masks which command types trigger the webhook.
- Lua (`EVAL`/`EVALSHA`) keeps multi-step matching server-side and atomic.

**Next:** [IoT Roaming Geofences](/tutorials/iot-roaming/): ROAM and geofences that follow a moving object.
