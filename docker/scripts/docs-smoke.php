<?php

declare(strict_types=1);

/**
 * Runs the code examples from the docs tutorials against a live Tile38 server,
 * verifying the documented API stays correct. Not a unit/integration test suite;
 * this is a smoke script for the documentation examples.
 *
 * Usage (from the docker test image, which has ext-redis):
 *   TILE38_HOST=127.0.0.1 TILE38_PORT=9851 TILE38_VERSION=1.38.0 \
 *     docker compose run --rm test php docker/scripts/docs-smoke.php
 */

require __DIR__ . '/../../vendor/autoload.php';

use RonAppleton\GeoJson\Enums\GeoJsonType;
use RonAppleton\GeoJson\Objects\Factory;
use Ronappleton\Tile38PhpClient\Clients\Tile38;
use Ronappleton\Tile38PhpClient\Commands\Objects\Bounds;
use Ronappleton\Tile38PhpClient\Commands\Objects\Circle;
use Ronappleton\Tile38PhpClient\Commands\Objects\GeoHash;
use Ronappleton\Tile38PhpClient\Commands\Objects\GeoJson;
use Ronappleton\Tile38PhpClient\Commands\Objects\Point;
use Ronappleton\Tile38PhpClient\Commands\Objects\Roam;
use Ronappleton\Tile38PhpClient\Commands\Objects\StringValue;
use Ronappleton\Tile38PhpClient\Enums\SearchType;

$host = getenv('TILE38_HOST') ?: '127.0.0.1';
$port = (int) (getenv('TILE38_PORT') ?: '9851');
$version = getenv('TILE38_VERSION') ?: '';

$client = new Tile38($host, $port);
$client->output('json');

$passed = 0;
$failed = 0;

/**
 * @param callable(): mixed $example
 */
function check(string $label, callable $example): void
{
    global $passed, $failed;

    try {
        $result = $example();
        if ($result === false || $result === null) {
            throw new RuntimeException('returned a falsy value');
        }
        $passed++;
        echo "PASS  $label\n";
    } catch (Throwable $e) {
        $failed++;
        echo "FAIL  $label  ({$e->getMessage()})\n";
    }
}

function atLeast(string $min): bool
{
    global $version;

    return $version === '' || version_compare($version, $min, '>=');
}

function uniqueKey(): string
{
    return 'smoke:' . bin2hex(random_bytes(4));
}

function decode(mixed $raw): array
{
    if (is_string($raw)) {
        $decoded = json_decode($raw, true);
        if (is_array($decoded)) {
            return $decoded;
        }
    }

    return (array) $raw;
}

// ---- Quick Start ----------------------------------------------------------
$fleet = uniqueKey();
check('quick start: set a point', fn () => $client->set($fleet, 'truck1', Point::make(51.5007, -0.1246))->execute());
check('quick start: get the point', function () use ($client, $fleet) {
    $r = decode($client->get($fleet, 'truck1')->execute());

    return ($r['object']['type'] ?? null) === 'Point';
});
check('quick start: nearby search', function () use ($client, $fleet) {
    $r = decode($client->nearby($fleet, Point::make(51.5074, -0.1278, 5000))
        ->limit(10)->distance()->points()->execute());

    return ($r['count'] ?? 0) >= 1;
});

// ---- Object types ---------------------------------------------------------
$objects = uniqueKey();
check('object types: bounds', fn () => $client->set($objects, 'house1', Bounds::make(51.30, -0.30, 51.70, 0.10))->execute());
check('object types: geohash', fn () => $client->set($objects, 'area1', GeoHash::make('gcpvj0'))->execute());

$lineString = Factory::make(GeoJsonType::LineString);
$a = Factory::make(GeoJsonType::Point);
$a->setPoints(-0.1, 51.5);
$b = Factory::make(GeoJsonType::Point);
$b->setPoints(-0.2, 51.6);
$lineString->addPoints($a, $b);
check('object types: geojson linestring', function () use ($client, $objects, $lineString) {
    $client->set($objects, 'route1', GeoJson::make($lineString))->execute();

    $r = decode($client->get($objects, 'route1')->execute());

    return ($r['object']['type'] ?? null) === 'LineString';
});
check('object types: string value', fn () => $client->set($objects, 'note', StringValue::make('London'))->execute());

// ---- Search ---------------------------------------------------------------
check('search: scan ids', function () use ($client, $objects) {
    $r = decode($client->scan($objects)->ids()->execute());

    return ($r['count'] ?? 0) >= 1;
});
check('search: nearby with where', function () use ($client, $fleet) {
    $client->set($fleet, 'truck2', Point::make(51.51, -0.13))->field('speed', 90)->execute();

    $r = decode($client->nearby($fleet, Point::make(51.5074, -0.1278, 5000))
        ->where('speed', 70, '+inf')->ids()->execute());

    return in_array('truck2', $r['ids'] ?? [], true);
});
check('search: within bounds', function () use ($client, $objects) {
    $r = decode($client->within($objects, Bounds::make(51.0, -1.0, 52.0, 0.5))->ids()->execute());

    return ($r['count'] ?? 0) >= 1;
});
check('search: intersects circle', function () use ($client, $objects) {
    $r = decode($client->intersects($objects, Circle::make(51.5, -0.1, 50000))->ids()->execute());

    return ($r['count'] ?? 0) >= 1;
});
if (atLeast('1.17.0')) {
    check('search: timeout wrapper', function () use ($client, $objects) {
        $r = decode($client->scan($objects)->count()->timeout(0.5)->execute());

        return isset($r['count']);
    });
}

// ---- Postcode lookup ------------------------------------------------------
$postcodes = uniqueKey();
check('postcode: store a postcode', function () use ($client, $postcodes) {
    $client->set($postcodes, 'SW1A1AA', Point::make(51.5007, -0.1246))->execute();
    $client->set($postcodes, 'SW1A2AA', Point::make(51.5035, -0.1287))->execute();

    return true;
});
check('postcode: forward lookup', function () use ($client, $postcodes) {
    $r = decode($client->get($postcodes, 'SW1A1AA')->point()->execute());

    return isset($r['point']);
});
check('postcode: reverse lookup', function () use ($client, $postcodes) {
    $r = decode($client->nearby($postcodes, Point::make(51.5012, -0.1252, 1000))->ids()->execute());

    return in_array('SW1A1AA', $r['ids'] ?? [], true);
});

// ---- Fields / lifecycle ---------------------------------------------------
$tools = uniqueKey();
check('lifecycle: set with field and expiry', function () use ($client, $tools) {
    $client->set($tools, 'drill-01', Point::make(51.50, -0.12))->field('weight', 10)->ex(86400)->execute();

    return true;
});
check('lifecycle: ttl', function () use ($client, $tools) {
    $r = decode($client->ttl($tools, 'drill-01')->execute());

    return isset($r['ttl']);
});
check('lifecycle: persist', fn () => $client->persist($tools, 'drill-01')->execute());
check('lifecycle: get with fields', function () use ($client, $tools) {
    $r = decode($client->get($tools, 'drill-01')->withfields()->execute());

    return ($r['fields']['weight'] ?? null) === 10;
});
if (atLeast('1.30.0')) {
    check('lifecycle: non-numeric field (1.30+)', function () use ($client, $tools) {
        $client->set($tools, 'drill-02', Point::make(51.51, -0.13))->field('site', 'site-a')->execute();
        $r = decode($client->get($tools, 'drill-02')->withfields()->execute());

        return ($r['fields']['site'] ?? null) === 'site-a';
    });
}

// ---- JSON / fields --------------------------------------------------------
$meta = uniqueKey();
check('jset/jget/jdel', function () use ($client, $meta) {
    $client->jset($meta, 'doc', 'location.lat', 33.5)->execute();
    $r = decode($client->jget($meta, 'doc', 'location.lat')->execute());

    return isset($r['value']);
});
check('fset', function () use ($client, $fleet) {
    $client->fset($fleet, 'truck1', 'speed', 90)->execute();
    $r = decode($client->get($fleet, 'truck1')->withfields()->execute());

    return ($r['fields']['speed'] ?? null) === 90;
});
if (atLeast('1.33.0')) {
    check('exists/fexists/fget', function () use ($client, $fleet) {
        $exists = decode($client->exists($fleet, 'truck1')->execute());
        $fexists = decode($client->fexists($fleet, 'truck1', 'speed')->execute());
        $fget = decode($client->fget($fleet, 'truck1', 'speed')->execute());

        return ($exists['exists'] ?? false) === true
            && ($fexists['exists'] ?? false) === true
            && isset($fget['value']);
    });
}

// ---- Geofencing / webhooks ------------------------------------------------
if (atLeast('1.13.0')) {
    $chan = uniqueKey();
    check('geofence: setchan', function () use ($client, $chan, $fleet) {
        return $client->setchan($chan, SearchType::Nearby, $fleet, Point::make(51.5, -0.12, 500))
            ->detect('enter,exit')->fence()->execute();
    });
    check('geofence: chans lists it', function () use ($client, $chan) {
        $r = decode($client->chans('*')->execute());
        $names = array_column($r['chans'] ?? [], 'name');

        return in_array($chan, $names, true);
    });
    check('geofence: delchan', fn () => $client->delchan($chan)->execute());

    $hook = uniqueKey();
    check('webhook: sethook', function () use ($client, $hook, $fleet) {
        return $client->sethook(
            $hook,
            'http://example.com/hook',
            SearchType::Nearby,
            $fleet,
            Point::make(51.5, -0.12, 500),
        )->fence()->execute();
    });
    check('webhook: hooks lists it', function () use ($client, $hook) {
        $r = decode($client->hooks('*')->execute());
        $names = array_column($r['hooks'] ?? [], 'name');

        return in_array($hook, $names, true);
    });
    check('webhook: delhook', fn () => $client->delhook($hook)->execute());
}

// ---- Scripting ------------------------------------------------------------
if (atLeast('1.10.0')) {
    check('scripting: eval', function () use ($client) {
        $r = decode($client->eval('return 2 * 3', 0)->execute());

        return ($r['result'] ?? null) === 6;
    });
    check('scripting: script load/exists', function () use ($client) {
        $load = decode($client->script('load', 'return 1')->execute());
        $sha = $load['result'] ?? null;
        if (!is_string($sha)) {
            return false;
        }
        $exists = decode($client->script('exists', $sha)->execute());

        return ($exists['result'] ?? []) === [1];
    });
    check('scripting: script flush', fn () => $client->script('flush')->execute());
}

// ---- Real estate / GeoJSON polygon ----------------------------------------
$props = uniqueKey();
check('real estate: store a property polygon', function () use ($client, $props) {
    $polygon = Factory::make(GeoJsonType::Polygon);
    $p = static fn (float $lon, float $lat) => (Factory::make(GeoJsonType::Point))->setPoints($lon, $lat);
    $polygon->setExteriorRing($p(-0.14, 51.50), $p(-0.13, 51.50), $p(-0.13, 51.51), $p(-0.14, 51.51), $p(-0.14, 51.50));

    return $client->set($props, 'p-1001', GeoJson::make($polygon))->field('price', 425000)->execute();
});
check('real estate: intersects a box', function () use ($client, $props) {
    $r = decode($client->intersects($props, Bounds::make(51.49, -0.15, 51.52, -0.12))
        ->where('price', 300000, 600000)->ids()->execute());

    return in_array('p-1001', $r['ids'] ?? [], true);
});

// ---- IoT roaming ----------------------------------------------------------
if (atLeast('1.13.0')) {
    $cars = uniqueKey();
    $trackers = uniqueKey();
    check('roaming: setchan with roam area', function () use ($client, $cars, $trackers) {
        $client->set($cars, 'car-7', Point::make(51.5012, -0.1252))->execute();
        $client->set($trackers, 'child-a', Point::make(51.5020, -0.1260))->execute();

        return $client->setchan(uniqueKey(), SearchType::Nearby, $trackers, Roam::make($cars, 'car-7', 100.0))
            ->detect('enter,exit')->fence()->execute();
    });
}

echo "\nDocs smoke: {$passed} passed, {$failed} failed.\n";
exit($failed > 0 ? 1 : 0);
