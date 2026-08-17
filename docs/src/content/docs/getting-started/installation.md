---
title: Installation
description: Requirements and installation steps for the Tile38 PHP client.
---

## Requirements

- **PHP >= 8.1**
- **ext-redis**: the [phpredis](https://github.com/phpredis/phpredis) extension
- **A Tile38 server**: 1.13.0 or newer (see
  [Version Compatibility](/reference/version-compatibility/))
- **php-geojson**: installed automatically as a dependency (used for GeoJSON
  objects)

## Install

```bash
composer require ronappleton/tile38-php-client
```

The package depends on `ronappleton/php-geojson` for building GeoJSON objects,
so it is installed alongside automatically.

> If you do not have Tile38 running yet, the quickest way to start one is
> Docker:

```bash
docker run -d -p 9851:9851 tile38/tile38
```

## Verify

Create a quick sanity check:

```php
<?php

require 'vendor/autoload.php';

use Ronappleton\Tile38PhpClient\Clients\Tile38;

$client = new Tile38('127.0.0.1', 9851);

var_dump($client->ping()->execute()); // "PONG"
```

## Next steps

- [Quick Start](/getting-started/quick-start/): your first `set`, `get`, and
  `nearby`.
- [Configuration](/getting-started/configuration/): connection options in
  detail.
