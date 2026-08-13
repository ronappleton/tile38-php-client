<?php

declare(strict_types=1);

namespace Ronappleton\Tile38PhpClient\Tests\Integration;

use PHPUnit\Framework\TestCase;
use Ronappleton\Tile38PhpClient\Commands\Objects\Point;

class KeyMetadataTest extends IntegrationTestCase
{
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
