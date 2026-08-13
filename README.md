# tile38-php-client
A php client for the Tile38 Ultra Fast Geospatial Database

[![License](http://poser.pugx.org/ronappleton/tile38-php-client/license)](https://packagist.org/packages/ronappleton/tile38-php-client)
[![PHP Version Require](http://poser.pugx.org/ronappleton/tile38-php-client/require/php)](https://packagist.org/packages/ronappleton/tile38-php-client)
[![Codacy Badge](https://app.codacy.com/project/badge/Grade/71b6bf0f18b743fc97e6feadc42e7a1a)](https://www.codacy.com/gh/ronappleton/tile38-php-client/dashboard?utm_source=github.com&amp;utm_medium=referral&amp;utm_content=ronappleton/tile38-php-client&amp;utm_campaign=Badge_Grade)

GeoJSON objects are provided by the [php-geojson](https://github.com/ronappleton/php-geojson) object builder, which is a dependency of this package.

## Installation

```bash
composer require ronappleton/tile38-php-client
```

This library requires PHP >= 8.1 and the `ext-redis` extension.

## Usage

```php
use RonAppleton\GeoJson\Enums\GeoJsonType;
use RonAppleton\GeoJson\Objects\Factory;
use Ronappleton\Tile38PhpClient\Clients\Tile38;
use Ronappleton\Tile38PhpClient\Commands\Objects\GeoJson;
use Ronappleton\Tile38PhpClient\Commands\Objects\Point;
use Ronappleton\Tile38PhpClient\Enums\SearchType;

$client = new Tile38('127.0.0.1', 9851);

// Set a point.
$client->set('fleet', 'truck1', Point::make(33.5123, -112.2693))->execute();

// Set a GeoJSON object built with php-geojson.
$point = Factory::make(GeoJsonType::Point);
$point->setPoints(-112.2693, 33.5123);

$client->set('cities', 'tempe', GeoJson::make($point))->execute();

// Search with options.
$client->nearby('fleet', Point::make(33.462, -112.268, 6000))
    ->limit(10)
    ->distance()
    ->points()
    ->execute();

// Create a geofenced pub/sub channel.
$client->setchan('warehouse', SearchType::Nearby, 'fleet', Point::make(33.5123, -112.2693, 500))
    ->fence()
    ->detect('enter,exit')
    ->execute();
```

## Commands Covered

The **Since** column is the first Tile38 release the command is known to work with. The integration test
suite (see below) verifies every implemented command against Tile38 `1.13.0`, `1.17.0`, `1.22.0`,
`1.25.0`, `1.30.0`, `1.33.0` and `1.38.0`.

| Group       | Command Name    | Command | Since   |
|-------------|-----------------|---------|---------|
| Channels    | CHANS           | ✅       | 1.13.0  |
| Channels    | DELCHAN         | ✅       | 1.13.0  |
| Channels    | PDELCHAN        | ✅       | 1.13.0  |
| Channels    | PSUBSCRIBE      | ✅       | 1.13.0  |
| Channels    | SETCHAN         | ✅       | 1.13.0  |
| Channels    | SUBSCRIBE       | ✅       | 1.13.0  |
| ---------   | ----------      | ------- | ------- |
| Connection  | AUTH            | ✅       | 1.0.0   |
| Connection  | OUTPUT          | ✅       | 1.1.0   |
| Connection  | PING            | ✅       | 1.0.0   |
| Connection  | QUIT            | ✅       | 1.0.0   |
| Connection  | TIMEOUT         | ✅       | 1.17.0  |
| ---------   | ----------      | ------- | ------- |
| Keys        | BOUNDS          | ✅       | 1.3.0   |
| Keys        | DEL             | ✅       | 1.0.0   |
| Keys        | DROP            | ✅       | 1.0.0   |
| Keys        | EXISTS          | ✅       | 1.33.0  |
| Keys        | EXPIRE          | ✅       | 1.0.0   |
| Keys        | FEXISTS         | ✅       | 1.33.0  |
| Keys        | FGET            | ✅       | 1.33.0  |
| Keys        | FSET            | ✅       | 1.0.0   |
| Keys        | GET             | ✅       | 1.0.0   |
| Keys        | JDEL            | ✅       | 1.7.0   |
| Keys        | JGET            | ✅       | 1.7.0   |
| Keys        | JSET            | ✅       | 1.7.0   |
| Keys        | KEYS            | ✅       | 1.0.0   |
| Keys        | PDEL            | ✅       | 1.7.0   |
| Keys        | PERSIST         | ✅       | 1.0.0   |
| Keys        | RENAME          | ✅       | 1.14.5  |
| Keys        | RENAMENX        | ✅       | 1.14.5  |
| Keys        | SET             | ✅       | 1.0.0   |
| Keys        | STATS           | ✅       | 1.0.0   |
| Keys        | TTL             | ✅       | 1.0.0   |
| ---------   | ----------      | ------- | ------- |
| Scripting   | EVAL            | ✅       | 1.10.0  |
| Scripting   | EVALNA          | ✅       | 1.10.0  |
| Scripting   | EVALNASHA       | ✅       | 1.10.0  |
| Scripting   | EVALRO          | ✅       | 1.10.0  |
| Scripting   | EVALROSHA       | ✅       | 1.10.0  |
| Scripting   | EVALSHA         | ✅       | 1.10.0  |
| Scripting   | SCRIPT EXISTS   | ✅       | 1.10.0  |
| Scripting   | SCRIPT FLUSH    | ✅       | 1.10.0  |
| Scripting   | SCRIPT LOAD     | ✅       | 1.10.0  |
| ---------   | ----------      | ------- | ------- |
| Search      | INTERSECTS      | ✅       | 1.0.0   |
| Search      | NEARBY          | ✅       | 1.0.0   |
| Search      | SCAN            | ✅       | 1.0.0   |
| Search      | SEARCH          | ✅       | 1.4.2   |
| Search      | WITHIN          | ✅       | 1.0.0   |
| Server      | CONFIG GET      | ✅       | 1.0.0   |
| Server      | CONFIG REWRITE  | ✅       | 1.0.0   |
| Server      | CONFIG SET      | ✅       | 1.0.0   |
| Server      | FLUSHDB         | ✅       | 1.0.0   |
| Server      | GC              | ✅       | 1.0.0   |
| Server      | HEALTHZ         | ✅       | 1.24.1  |
| Server      | INFO            | ✅       | 1.0.0   |
| Server      | READONLY        | ✅       | 1.0.0   |
| Server      | ROLE            | ✅       | 1.32.0  |
| Server      | SERVER          | ✅       | 1.0.0   |
| ---------   | ----------      | ------- | ------- |
| Replication | AOF             | ✅       | 1.0.0   |
| Replication | AOFMD5          | ✅       | 1.0.0   |
| Replication | AOFSHRINK       | ✅       | 1.0.0   |
| Replication | FOLLOW          | ✅       | 1.0.0   |
| ---------   | ----------      | ------- | ------- |
| Webhooks    | DELHOOK         | ✅       | 1.0.0   |
| Webhooks    | HOOKS           | ✅       | 1.0.0   |
| Webhooks    | PDELHOOK        | ✅       | 1.0.0   |
| Webhooks    | SETHOOK         | ✅       | 1.0.0   |
| ---------   | ----------      | ------- | ------- |
| Utility     | TEST            | ✅       | 1.16.0  |
| Utility     | RAW             | ✅       | -       |

`RAW` is a client-side passthrough for sending arbitrary commands and is not a Tile38 command.

### Version notes

Options and features gated by Tile38 version:

| Option / feature                    | Since   |
|-------------------------------------|---------|
| `WITHFIELDS` on GET                 | 1.1.0   |
| `EX` / `STRING` on SET              | 1.3.0   |
| `NX` / `XX` on SET                  | 1.5.0   |
| `WHEREIN` clause                    | 1.10.0  |
| `WHEREEVAL` clause                  | 1.11.0  |
| `CLIP` (search)                     | 1.13.0  |
| `HEALTHZ` command                   | 1.24.1  |
| `CLIPBY` (search)                   | 1.25.0  |
| `SECTOR` area format                | 1.26.0  |
| `BUFFER` (WITHIN / INTERSECTS)      | 1.27.0  |
| WHERE filter expressions (`>`, `<`) | 1.30.0  |
| `ROLE` command                      | 1.32.0  |
| `EXISTS` / `FEXISTS` / `FGET`       | 1.33.0  |
| WHERE regex (`=~`)                  | 1.37.0  |
| `RX` on SET / FSET                  | 1.38.0  |

Note: CHANS returned malformed JSON on Tile38 versions before 1.17.3; HOOKS and other commands were unaffected.

## Integration Testing with Docker

A local Docker stack runs the full integration suite against multiple Tile38 releases. It builds a small
PHP image with `ext-redis` and phpredis, so no local PHP Redis extension is required.

```bash
# Build the PHP test image and run the integration suite against every Tile38 version.
docker compose up --build test

# Tear down the stack afterwards.
docker compose down
```

The versions under test are defined in `docker-compose.yml` (`TILE38_VERSIONS`). To run against a single
version:

```bash
docker compose up -d tile38-138
docker compose run --rm --no-deps \
    -e TILE38_HOST=tile38-138 \
    -e TILE38_PORT=9851 \
    -e TILE38_VERSION=1.38.0 \
    test vendor/bin/phpunit --configuration phpunit-integration.xml
```

Or, with a Tile38 instance already running locally:

```bash
TILE38_HOST=127.0.0.1 TILE38_PORT=9851 TILE38_VERSION=1.38.0 composer test:integration
```

## Testing

```bash
composer test          # run the unit test suite (no server required)
composer cs            # check code style
composer cs:fix        # automatically fix code style
```

## Documentation Site

A full documentation site (Starlight/Astro) lives in `docs/` and ships as its own
Docker image. It covers installation, the fluent API, every command, version
compatibility, guides, and real-world tutorials.

```bash
# Serve the built site on http://localhost:3000
docker compose up --build docs

# Or run the docs dev server with hot reload on http://localhost:4321
docker compose up docs-dev
```

To build the docs locally:

```bash
cd docs
npm install
npm run generate:commands   # regenerate the command reference from CommandRegistry
npm run build               # outputs to docs/dist
```
