---
title: Asset Check-In
description: "Simple: track equipment check-ins with automatic expiry and TTL."
---

**Level:** Simple

Tools are signed out from a warehouse. Each check-out should auto-expire after a
set time: no cron job required.

## Check a tool out

`EX` sets an expiry in seconds on the object:

```php
use Ronappleton\Tile38PhpClient\Clients\Tile38;
use Ronappleton\Tile38PhpClient\Commands\Objects\Point;

$client = new Tile38('127.0.0.1', 9851);

// Tool "drill-01" checked out to site A, expires in 24 hours.
$client->set('tools', 'drill-01', Point::make(51.5007, -0.1246))
    ->field('site', 'site-a')
    ->field('worker', 'alice')
    ->ex(86400)
    ->execute();
```

> String field values like `site-a` require Tile38 1.30.0+; on older versions
> `FIELD` only accepted numbers (see
> [Version Compatibility](/reference/version-compatibility/)).

## Check the remaining time

```php
$result = $client->ttl('tools', 'drill-01')->execute();
// {"ok":true,"ttl":86180,...}
```

## Cancel the expiry

A tool returned early: clear the timeout:

```php
$client->persist('tools', 'drill-01')->execute();
```

## "What's still out?"

Expired tools disappear automatically, so a scan only returns live check-outs:

```php
$result = $client->scan('tools')->ids()->execute();
```

## Guard against double check-out

Use `NX` to fail if the id already exists:

```php
try {
    $client->set('tools', 'drill-01', Point::make(51.5007, -0.1246))
        ->ex(86400)
        ->nx()
        ->execute();
    // success: tool was free
} catch (Throwable) {
    // already checked out
}
```

`XX` does the opposite: only update an existing object.

## What you learned

- `->ex(seconds)` expires an object automatically.
- `->persist()` removes an expiry; `->ttl()` reads the remaining time.
- `->nx()` / `->xx()` make check-out atomic.

**Next:** [Postcode & Geocoding Lookup](/tutorials/postcode-lookup/): resolve coordinates from place keys and back again.
