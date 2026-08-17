<?php

declare(strict_types=1);

namespace Ronappleton\Tile38PhpClient\Tests\Unit\Commands;

use PHPUnit\Framework\TestCase;
use Ronappleton\Tile38PhpClient\Commands\Objects\Bounds;
use Ronappleton\Tile38PhpClient\Commands\Objects\Circle;
use Ronappleton\Tile38PhpClient\Commands\Objects\Point;
use Ronappleton\Tile38PhpClient\Commands\Objects\Roam;
use Ronappleton\Tile38PhpClient\Commands\Search\Intersects;
use Ronappleton\Tile38PhpClient\Commands\Search\Nearby;
use Ronappleton\Tile38PhpClient\Commands\Search\Scan;
use Ronappleton\Tile38PhpClient\Commands\Search\Search;
use Ronappleton\Tile38PhpClient\Commands\Search\Within;
use Ronappleton\Tile38PhpClient\Tests\Support\RedisStub;

class SearchCommandTest extends TestCase
{
    public function testScanWithOptions(): void
    {
        $redis = new RedisStub();

        $command = new Scan($redis, ['fleet']);
        $command->where('speed', 70, '+inf')->match('truck*')->count();

        $command->execute();

        self::assertSame(
            [['SCAN', 'fleet', 'WHERE', 'speed', '70', '+inf', 'MATCH', 'truck*', 'COUNT']],
            $redis->recordedCommands,
        );
    }

    public function testSearchWithOrdering(): void
    {
        $redis = new RedisStub();

        $command = new Search($redis, ['names']);
        $command->match('J*')->desc()->ids();

        $command->execute();

        self::assertSame(
            [['SEARCH', 'names', 'MATCH', 'J*', 'DESC', 'IDS']],
            $redis->recordedCommands,
        );
    }

    public function testNearbyWithAreaAndOptions(): void
    {
        $redis = new RedisStub();

        $command = new Nearby(
            $redis,
            ['fleet', Point::make(33.462, - 112.268, 6000)],
        );
        $command->limit(1)->distance()->points();

        $command->execute();

        self::assertSame(
            [['NEARBY', 'fleet', 'LIMIT', '1', 'DISTANCE', 'POINTS', 'POINT', '33.462', '-112.268', '6000']],
            $redis->recordedCommands,
        );
    }

    public function testNearbyLimitWithoutRadiusFindsNearestObjects(): void
    {
        $redis = new RedisStub();

        $command = new Nearby(
            $redis,
            ['stores', Point::make(51.5074, - 0.1278)],
        );
        $command->limit(3)->distance()->points();

        $command->execute();

        self::assertSame(
            [['NEARBY', 'stores', 'LIMIT', '3', 'DISTANCE', 'POINTS', 'POINT', '51.5074', '-0.1278']],
            $redis->recordedCommands,
        );
    }

    public function testWithinWithBoundsArea(): void
    {
        $redis = new RedisStub();

        $command = new Within(
            $redis,
            ['fleet', Bounds::make(33.462, - 112.268, 33.491, - 112.245)],
        );
        $command->buffer(100.0)->count();

        $command->execute();

        self::assertSame(
            [['WITHIN', 'fleet', 'BUFFER', '100', 'COUNT', 'BOUNDS', '33.462', '-112.268', '33.491', '-112.245']],
            $redis->recordedCommands,
        );
    }

    public function testIntersectsWithCircleArea(): void
    {
        $redis = new RedisStub();

        $command = new Intersects(
            $redis,
            ['fleet', Circle::make(33.462, - 112.268, 6000)],
        );
        $command->ids();

        $command->execute();

        self::assertSame(
            [['INTERSECTS', 'fleet', 'IDS', 'CIRCLE', '33.462', '-112.268', '6000']],
            $redis->recordedCommands,
        );
    }

    public function testTimeoutWrapsSearchCommand(): void
    {
        $redis = new RedisStub();

        $command = new Scan($redis, ['fleet']);
        $command->count()->timeout(0.5);

        $command->execute();

        self::assertSame(
            [['TIMEOUT', '0.5', 'SCAN', 'fleet', 'COUNT']],
            $redis->recordedCommands,
        );
    }

    public function testTimeoutWithoutTimeoutIsUnchanged(): void
    {
        $redis = new RedisStub();

        $command = new Scan($redis, ['fleet']);
        $command->execute();

        self::assertSame([['SCAN', 'fleet']], $redis->recordedCommands);
    }

    public function testNearbyWithRoamArea(): void
    {
        $redis = new RedisStub();

        $command = new Nearby(
            $redis,
            ['fleet', Roam::make('fleet', 'truck*', 500.0)],
        );
        $command->fence();

        $command->execute();

        self::assertSame(
            [['NEARBY', 'fleet', 'FENCE', 'ROAM', 'fleet', 'truck*', '500']],
            $redis->recordedCommands,
        );
    }

    public function testWithinWithClipBy(): void
    {
        $redis = new RedisStub();

        $command = new Within(
            $redis,
            ['fleet', Bounds::make(33.462, - 112.268, 33.491, - 112.245)],
        );
        $command->clipby(Bounds::make(33.46, - 112.26, 33.49, - 112.24));

        $command->execute();

        self::assertSame(
            [[
                'WITHIN', 'fleet', 'CLIPBY', 'BOUNDS', '33.46', '-112.26',
                '33.49', '-112.24', 'BOUNDS', '33.462', '-112.268', '33.491', '-112.245',
            ]],
            $redis->recordedCommands,
        );
    }
}
