# tile38-php-client
A php client for the Tile38 Ultra Fast Geospatial Database

[![License](http://poser.pugx.org/ronappleton/tile38-php-client/license)](https://packagist.org/packages/ronappleton/tile38-php-client)
[![PHP Version Require](http://poser.pugx.org/ronappleton/tile38-php-client/require/php)](https://packagist.org/packages/ronappleton/tile38-php-client)
[![Codacy Badge](https://app.codacy.com/project/badge/Grade/71b6bf0f18b743fc97e6feadc42e7a1a)](https://www.codacy.com/gh/ronappleton/tile38-php-client/dashboard?utm_source=github.com&amp;utm_medium=referral&amp;utm_content=ronappleton/tile38-php-client&amp;utm_campaign=Badge_Grade)

GeoJSON objects are provided by the [php-geojson](https://github.com/ronappleton/php-geojson) object builder, which is a dependency of this package.

## Commands Covered - Updated as Implemented

| Group       | Command Name    | Command   |
|-------------|-----------------|-----------|
| Channels    | CHANS           | ✅         |
| Channels    | DELCHAN         | ❌         |
| Channels    | PDELCHAN        | ❌         |
| Channels    | PSUBSCRIBE      | ❌         |
| Channels    | SETCHAN         | ❌         |
| Channels    | SUBSCRIBE       | ❌         |
| ---------   | ----------      | -------   |
| Connection  | AUTH            | ✅         |
| Connection  | OUTPUT          | ✅         |
| Connection  | PING            | ✅         |
| Connection  | QUIT            | ✅         |
| Connection  | TIMEOUT         | ✅         |
| ---------   | ----------      | -------   |
| Keys        | BOUNDS          | ✅         |
| Keys        | DEL             | ✅         |
| Keys        | DROP            | ✅         |
| Keys        | EXPIRE          | ✅         |
| Keys        | FSET            | ✅         |
| Keys        | GET             | ✅         |
| Keys        | JDEL            | ✅         |
| Keys        | JGET            | ✅         |
| Keys        | JSET            | ✅         |
| Keys        | KEYS            | ✅         |
| Keys        | PDEL            | ✅         |
| Keys        | PERSIST         | ✅         |
| Keys        | RENAME          | ✅         |
| Keys        | RENAMENX        | ✅         |
| Keys        | SET             | ✅         |
| Keys        | STATS           | ✅         |
| Keys        | TTL             | ✅         |
| ---------   | ----------      | -------   |
| Scripting   | EVAL            | ❌         |
| Scripting   | EVALNA          | ❌         |
| Scripting   | EVALNASHA       | ❌         |
| Scripting   | EVALRO          | ❌         |
| Scripting   | EVALROSHA       | ❌         |
| Scripting   | EVALSHA         | ❌         |
| Scripting   | SCRIPT EXISTS   | ❌         |
| Scripting   | SCRIPT FLUSH    | ❌         |
| Scripting   | SCRIPT LOAD     | ❌         |
| ---------   | ----------      | -------   |
| Search      | INTERSECTS      | ✅         |
| Search      | NEARBY          | ✅         |
| Search      | SCAN            | ✅         |
| Search      | SEARCH          | ✅         |
| Search      | WITHIN          | ✅         |
| Server      | CONFIG GET      | ❌         |
| Server      | CONFIG REWRITE  | ❌         |
| Server      | CONFIG SET      | ❌         |
| Server      | FLUSHDB         | ❌         |
| Server      | GC              | ❌         |
| Server      | READONLY        | ❌         |
| Server      | SERVER          | ✅         |
| ---------   | ----------      | -------   |
| Webhooks    | DELHOOK         | ❌         |
| Webhooks    | HOOKS           | ❌         |
| Webhooks    | PDELHOOK        | ❌         |
| Webhooks    | SETHOOK         | ❌         |
| ---------   | ----------      | -------   |
| Utility     | TEST            | ❌         |
| Utility     | RAW             | ✅         |

## Usage

```php
use RonAppleton\GeoJson\Enums\GeoJsonType;
use RonAppleton\GeoJson\Objects\Factory;
use Ronappleton\Tile38PhpClient\Clients\Tile38;
use Ronappleton\Tile38PhpClient\Commands\Objects\GeoJson;
use Ronappleton\Tile38PhpClient\Commands\Objects\Point;

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
```

## Testing

```bash
composer test     # run the test suite (phpunit)
composer cs       # check code style
composer cs:fix   # automatically fix code style
```
