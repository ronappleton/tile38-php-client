<?php

declare(strict_types=1);

namespace Ronappleton\Tile38PhpClient\Tests\Integration;

use PHPUnit\Framework\TestCase;
use Ronappleton\Tile38PhpClient\Clients\Tile38;
use Ronappleton\Tile38PhpClient\Commands\Objects\Bounds;
use Ronappleton\Tile38PhpClient\Commands\Objects\Circle;
use Ronappleton\Tile38PhpClient\Commands\Objects\Point;
use Ronappleton\Tile38PhpClient\Commands\Objects\StringValue;

class SearchTest extends IntegrationTestCase
{
    public function testScan(): void
    {
        $key = $this->uniqueKey();

        $this->client()->set($key, 'truck1', Point::make(33.5123, - 112.2693))->execute();
        $this->client()->set($key, 'truck2', Point::make(33.4626, - 112.1695))->execute();

        $response = $this->jsonResponse($this->client()->scan($key)->ids()->execute());

        TestCase::assertSame(['truck1', 'truck2'], $response['ids'] ?? null);
    }

    public function testScanWithWhereAndMatch(): void
    {
        $key = $this->uniqueKey();

        $this->client()->set($key, 'truck1', Point::make(33.5123, - 112.2693))->field('speed', 90)->execute();
        $this->client()->set($key, 'car1', Point::make(33.4626, - 112.1695))->field('speed', 30)->execute();

        $command = $this->client()->scan($key)->ids();
        $command->where('speed', 70, '+inf')->match('truck*');

        $response = $this->jsonResponse($command->execute());

        TestCase::assertSame(['truck1'], $response['ids'] ?? null);
    }

    public function testSearch(): void
    {
        $key = $this->uniqueKey();

        $this->client()->set($key, 'driver1', StringValue::make('John'))->execute();
        $this->client()->set(
            $key,
            'driver2',
            StringValue::make('Jane'),
        )->execute();

        $command = $this->client()->search($key)->ids();
        $command->match('J*')->desc();

        $response = $this->jsonResponse($command->execute());

        TestCase::assertContains('driver1', $response['ids'] ?? []);
        TestCase::assertContains('driver2', $response['ids'] ?? []);
    }

    public function testNearby(): void
    {
        $key = $this->uniqueKey();

        $this->client()->set($key, 'truck1', Point::make(33.5123, - 112.2693))->execute();
        $this->client()->set($key, 'truck2', Point::make(33.4626, - 112.1695))->execute();

        $command = $this->client()->nearby($key, Point::make(33.462, - 112.268, 100000));
        $command->points()->limit(10);

        $response = $this->jsonResponse($command->execute());

        TestCase::assertSame(2, $response['count'] ?? null);
        TestCase::assertArrayHasKey('points', $response);
    }

    public function testNearbyWithoutRadiusUsesNearestLimit(): void
    {
        $key = $this->uniqueKey();

        $this->client()->set($key, 'store1', Point::make(51.5074, - 0.1278))->execute();
        $this->client()->set($key, 'store2', Point::make(51.5174, - 0.1278))->execute();

        $response = $this->jsonResponse(
            $this->client()
                ->nearby($key, Point::make(51.5074, - 0.1278))
                ->limit(1)
                ->ids()
                ->execute(),
        );

        TestCase::assertSame(['store1'], $response['ids'] ?? null);
    }

    public function testPipelineCanSeedMultipleObjects(): void
    {
        $key = $this->uniqueKey();

        $results = $this->client()->pipeline(static function (Tile38 $client) use ($key): void {
            $client->set($key, 'store1', Point::make(51.5074, - 0.1278))->execute();
            $client->set($key, 'store2', Point::make(51.5174, - 0.1278))->execute();
        });

        TestCase::assertCount(2, $results);

        $response = $this->jsonResponse($this->client()->scan($key)->ids()->execute());

        TestCase::assertCount(2, $response['ids'] ?? []);
    }

    public function testWithin(): void
    {
        $key = $this->uniqueKey();

        $this->client()->set($key, 'truck1', Point::make(33.5123, - 112.2693))->execute();
        $this->client()->set($key, 'truck2', Point::make(33.4626, - 112.1695))->execute();

        $command = $this->client()->within($key, Bounds::make(33.0, - 113.0, 34.0, - 112.0));
        $command->ids();

        $response = $this->jsonResponse($command->execute());

        TestCase::assertSame(['truck1', 'truck2'], $response['ids'] ?? null);
    }

    public function testIntersects(): void
    {
        $key = $this->uniqueKey();

        $this->client()->set($key, 'truck1', Point::make(33.5123, - 112.2693))->execute();

        $command = $this->client()->intersects($key, Circle::make(33.5123, - 112.2693, 1000));
        $command->ids();

        $response = $this->jsonResponse($command->execute());

        TestCase::assertSame(['truck1'], $response['ids'] ?? null);
    }

    public function testWithinWithBuffer(): void
    {
        $this->requireVersion('1.27.0');

        $key = $this->uniqueKey();

        $this->client()->set($key, 'truck1', Point::make(33.5123, - 112.2693))->execute();

        $command = $this->client()->within($key, Circle::make(33.5123, - 112.2693, 1000));
        $command->buffer(100000.0)->ids();

        $response = $this->jsonResponse($command->execute());

        TestCase::assertContains('truck1', $response['ids'] ?? []);
    }

    public function testTimeoutWrapsSearch(): void
    {
        $this->requireVersion('1.17.0');

        $key = $this->uniqueKey();

        $this->client()->set($key, 'truck1', Point::make(33.5123, - 112.2693))->execute();

        $command = $this->client()->scan($key)->count();
        $command->timeout(1.0);

        $response = $this->jsonResponse($command->execute());

        TestCase::assertSame(1, $response['count'] ?? null);
    }
}
