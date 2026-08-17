---
title: Lua Scripting
description: "EVAL, EVALSHA, and friends: running Lua inside Tile38."
---

Tile38 runs Lua 5.1 server-side. Scripts can call other Tile38 commands through
`tile38.call()` and `tile38.pcall()`, and are useful for multi-step operations
that must run atomically without round trips.

## EVAL

```php
$client->eval('return 2 * 3', 0)->execute();   // 6
```

The second argument is the number of **keys** that follow. Keys land in the
`KEYS` table, and extra arguments in `ARGV`:

```php
$client->eval(
    'return tile38.call("GET", KEYS[1], ARGV[1], "POINT")',
    1,
    'fleet',
    'truck1',
)->execute();
```

## The EVAL family

| Method          | Wire command | Atomicity |
| --------------- | ------------ | --------- |
| `->eval()`      | `EVAL`       | Full      |
| `->evalsha()`   | `EVALSHA`    | Full      |
| `->evalro()`    | `EVALRO`     | Read-only |
| `->evalrosha()` | `EVALROSHA`  | Read-only |
| `->evalna()`    | `EVALNA`     | None      |
| `->evalnasha()` | `EVALNASHA`  | None      |

- **Full**: no other command runs during the script.
- **Read-only**: no write command runs concurrently.
- **None**: commands may run concurrently.

The `-sha` variants run a script cached on the server by its SHA1 digest, which
skips recompiling on every call.

## Caching scripts

```php
// Load a script and keep its SHA1.
$load = $client->script('load', 'return tile38.call("GET", KEYS[1], ARGV[1])')->execute();
$sha = $load['result'];

// Run it by digest.
$client->evalsha($sha, 1, 'fleet', 'truck1')->execute();

// Inspect and flush the cache.
$client->script('exists', $sha)->execute();
$client->script('flush')->execute();
```

## Timeouts

Scripting commands respect `TIMEOUT`: see [Timeouts](/concepts/timeout/):

```php
$client->eval('local clock = os.clock; while clock() < 5 do end return 1', 0)
    ->timeout(0.25)
    ->execute();
```

## What's available

Scripts have access to the standard Lua libraries (`base`, `table`, `io`, `os`,
`string`, `math`, `debug`, `json`) plus helpers like `tile38.sha1hex()`,
`tile38.distance_to()`, `tile38.status_reply()`, and `tile38.error_reply()`.

Global variables cannot be created (use `local`). Script caching is in-memory
and is lost when the server restarts.

## Tutorial

See [Realtime Dispatch](/tutorials/driver-dispatch/) for scripting in a real
workflow.
