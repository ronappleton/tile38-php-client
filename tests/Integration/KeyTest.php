<?php

declare(strict_types=1);

namespace Ronappleton\Tile38PhpClient\Tests\Integration;

use PHPUnit\Framework\TestCase;
use RonAppleton\GeoJson\Enums\GeoJsonType;
use RonAppleton\GeoJson\Objects\Factory;
use Ronappleton\Tile38PhpClient\Commands\Objects\Bounds;
use Ronappleton\Tile38PhpClient\Commands\Objects\GeoHash;
use Ronappleton\Tile38PhpClient\Commands\Objects\GeoJson;
use Ronappleton\Tile38PhpClient\Commands\Objects\Point;
use Ronappleton\Tile38PhpClient\Commands\Objects\StringValue;

use function sprintf;

class KeyTest extends IntegrationTestCase
{
    public function testSetAndGetPoint(): void
    {
        $key = $this->uniqueKey();

        $this->client()->set($key, 'truck1', Point::make(33.5123, - 112.2693))->execute();

        $response = $this->jsonResponse($this->client()->get($key, 'truck1')->execute());

        TestCase::assertSame('Point', $response['object']['type'] ?? null);
        TestCase::assertSame([- 112.2693, 33.5123], $response['object']['coordinates'] ?? null);
    }

    public function testSetAndGetBounds(): void
    {
        $key = $this->uniqueKey();

        $this->client()->set($key, 'house1', Bounds::make(33.7840, - 112.1520, 33.7848, - 112.1512))->execute();

        $response = $this->jsonResponse($this->client()->get($key, 'house1')->execute());

        TestCase::assertSame('Polygon', $response['object']['type'] ?? null);
    }

    public function testSetAndGetGeohash(): void
    {
        $key = $this->uniqueKey();

        $this->client()->set($key, 'area1', GeoHash::make('9tbnwg'))->execute();

        $response = $this->jsonResponse($this->client()->get($key, 'area1')->execute());

        TestCase::assertArrayHasKey('object', $response);
    }

    public function testSetAndGetGeoJsonObject(): void
    {
        $key = $this->uniqueKey();

        $lineString = Factory::make(GeoJsonType::LineString);

        $first = Factory::make(GeoJsonType::Point);
        $first->setPoints(- 112.2, 33.4);

        $second = Factory::make(GeoJsonType::Point);
        $second->setPoints(- 112.1, 33.5);

        $lineString->addPoints($first, $second);

        $this->client()->set($key, 'route1', GeoJson::make($lineString))->execute();

        $response = $this->jsonResponse($this->client()->get($key, 'route1')->execute());

        TestCase::assertSame('LineString', $response['object']['type'] ?? null);
    }

    public function testSetStringAndGet(): void
    {
        $key = $this->uniqueKey();

        $this->client()->set($key, 'driver', StringValue::make('John Denton'))->execute();

        $response = $this->jsonResponse($this->client()->get($key, 'driver')->execute());

        TestCase::assertSame('John Denton', $response['object'] ?? null);
    }

    public function testSetWithFieldAndExpiry(): void
    {
        $key = $this->uniqueKey();

        $this->client()->set($key, 'truck1', Point::make(33.5, - 112.3))
            ->field('speed', 90)
            ->ex(60)
            ->execute();

        $response = $this->jsonResponse($this->client()->get($key, 'truck1')->withfields()->execute());

        TestCase::assertSame('90', (string) ($response['fields']['speed'] ?? ''));
    }

    public function testDel(): void
    {
        $key = $this->uniqueKey();

        $this->client()->set($key, 'truck1', Point::make(33.5, - 112.3))->execute();

        $this->client()->del($key, 'truck1')->execute();

        $response = $this->jsonResponse($this->client()->get($key, 'truck1')->execute());

        TestCase::assertArrayHasKey('err', $response);
    }

    public function testDrop(): void
    {
        $key = $this->uniqueKey();

        $this->client()->set($key, 'truck1', Point::make(33.5, - 112.3))->execute();

        $this->client()->drop($key)->execute();

        $response = $this->jsonResponse($this->client()->get($key, 'truck1')->execute());

        TestCase::assertArrayHasKey('err', $response);
    }

    public function testExpirePersistTtl(): void
    {
        $key = $this->uniqueKey();

        $this->client()->set($key, 'truck1', Point::make(33.5, - 112.3))->execute();
        $this->client()->expire($key, 'truck1', 60)->execute();

        $ttl = $this->jsonResponse($this->client()->ttl($key, 'truck1')->execute());

        TestCase::assertGreaterThanOrEqual(1, $ttl['ttl'] ?? 0);
        TestCase::assertLessThanOrEqual(60, $ttl['ttl'] ?? 0);

        $this->client()->persist($key, 'truck1')->execute();

        $ttl = $this->jsonResponse($this->client()->ttl($key, 'truck1')->execute());

        TestCase::assertSame(- 1, $ttl['ttl'] ?? null);
    }

    public function testExistsFexistsFget(): void
    {
        $this->requireVersion('1.33.0');

        $key = $this->uniqueKey();

        $this->client()->set($key, 'truck1', Point::make(33.5, - 112.3))->field('speed', 90)->execute();

        $exists = $this->jsonResponse($this->client()->exists($key, 'truck1')->execute());
        TestCase::assertTrue($exists['exists'] ?? false);

        $missing = $this->jsonResponse($this->client()->exists($key, 'missing')->execute());
        TestCase::assertFalse($missing['exists'] ?? true);

        $fexists = $this->jsonResponse($this->client()->fexists($key, 'truck1', 'speed')->execute());
        TestCase::assertTrue($fexists['exists'] ?? false);

        $fget = $this->jsonResponse($this->client()->fget($key, 'truck1', 'speed')->execute());

        TestCase::assertEquals(90, $fget['value'] ?? null);
    }

    public function testFset(): void
    {
        $key = $this->uniqueKey();

        $this->client()->set($key, 'truck1', Point::make(33.5, - 112.3))->execute();

        $this->client()->fset($key, 'truck1', 'speed', 90)->execute();

        $response = $this->jsonResponse($this->client()->get($key, 'truck1')->withfields()->execute());

        TestCase::assertSame('90', (string) ($response['fields']['speed'] ?? ''));
    }

    public function testJsetJgetJdel(): void
    {
        $key = $this->uniqueKey();

        $this->client()->jset($key, 'truck1', 'location.lat', 33.5)->execute();

        $jget = $this->jsonResponse($this->client()->jget($key, 'truck1', 'location.lat')->execute());

        TestCase::assertEquals(33.5, $jget['value'] ?? null);

        $this->client()->jdel($key, 'truck1', 'location')->execute();

        $jget = $this->jsonResponse($this->client()->jget($key, 'truck1', 'location.lat')->execute());

        TestCase::assertArrayNotHasKey('value', $jget);
    }

    public function testPdel(): void
    {
        $key = $this->uniqueKey();

        $this->client()->set($key, 'truck1', Point::make(33.5, - 112.3))->execute();
        $this->client()->set($key, 'car1', Point::make(33.6, - 112.4))->execute();

        $this->client()->pdel($key, 'truck*')->execute();

        $response = $this->jsonResponse($this->client()->get($key, 'truck1')->execute());

        TestCase::assertArrayHasKey('err', $response);
    }

    public function testRenameAndRenameNx(): void
    {
        $this->requireVersion('1.14.5');

        $key = $this->uniqueKey();
        $renamed = sprintf('%s-renamed', $key);

        $this->client()->set($key, 'truck1', Point::make(33.5, - 112.3))->execute();

        $this->client()->rename($key, $renamed)->execute();

        $response = $this->jsonResponse($this->client()->get($renamed, 'truck1')->execute());

        TestCase::assertTrue($response['ok'] ?? false);

        $this->client()->renameNx($renamed, sprintf('%s-nx', $key))->execute();

        $response = $this->jsonResponse($this->client()->stats($renamed)->execute());

        TestCase::assertTrue($response['ok'] ?? false);
    }

    public function testKeys(): void
    {
        $key = $this->uniqueKey();

        $this->client()->set($key, 'truck1', Point::make(33.5, - 112.3))->execute();

        $response = $this->jsonResponse($this->client()->keys('*')->execute());

        TestCase::assertArrayHasKey('keys', $response);
        TestCase::assertContains($key, $response['keys']);
    }

    public function testStats(): void
    {
        $key = $this->uniqueKey();

        $this->client()->set($key, 'truck1', Point::make(33.5, - 112.3))->execute();

        $response = $this->jsonResponse($this->client()->stats($key)->execute());

        TestCase::assertTrue($response['ok'] ?? false);
        TestCase::assertSame(1, $response['stats'][0]['num_objects'] ?? null);
    }

    public function testBounds(): void
    {
        $key = $this->uniqueKey();

        $this->client()->set($key, 'truck1', Point::make(33.5, - 112.3))->execute();

        $response = $this->jsonResponse($this->client()->bounds($key)->execute());

        TestCase::assertArrayHasKey('bounds', $response);
    }
}
