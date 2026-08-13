<?php

declare(strict_types=1);

namespace Ronappleton\Tile38PhpClient\Tests\Unit\Commands;

use Ronappleton\Tile38PhpClient\Commands\Key\Fset;
use Ronappleton\Tile38PhpClient\Commands\Key\Get;
use Ronappleton\Tile38PhpClient\Commands\Key\Jget;
use Ronappleton\Tile38PhpClient\Commands\Key\Set;
use Ronappleton\Tile38PhpClient\Commands\Objects\Bounds;
use Ronappleton\Tile38PhpClient\Commands\Objects\GeoHash;
use Ronappleton\Tile38PhpClient\Commands\Objects\Point;
use Ronappleton\Tile38PhpClient\Tests\Support\RedisStub;

class KeyCommandTest extends CommandTestCase
{
    protected const array COMMANDS = [
        'bounds' => ['fleet'],
        'del' => ['fleet', 'truck1'],
        'drop' => ['fleet'],
        'exists' => ['fleet', 'truck1'],
        'expire' => ['fleet', 'truck1', 60],
        'fexists' => ['fleet', 'truck1', 'speed'],
        'fget' => ['fleet', 'truck1', 'speed'],
        'fset' => ['fleet', 'truck1', 'speed', 90],
        'get' => ['fleet', 'truck1'],
        'jdel' => ['fleet', 'truck1', 'location'],
        'jget' => ['fleet', 'truck1', 'location'],
        'jset' => ['fleet', 'truck1', 'location.lat', 33.5],
        'keys' => ['*'],
        'pdel' => ['fleet', 'truck*'],
        'persist' => ['fleet', 'truck1'],
        'rename' => ['fleet', 'fleet2'],
        'renamenx' => ['fleet', 'fleet2'],
        'stats' => ['fleet'],
        'ttl' => ['fleet', 'truck1'],
    ];

    public function testSetPointWithOptions(): void
    {
        $redis = new RedisStub();

        $command = (new Set($redis, ['fleet', 'truck1', Point::make(33.5123, - 112.2693)]))
            ->field('speed', 90)
            ->ex(60)
            ->nx();

        $command->execute();

        $expected = [
            ['SET', 'fleet', 'truck1', 'FIELD', 'speed', '90', 'EX', '60', 'NX', 'POINT', '33.5123', '-112.2693'],
        ];

        self::assertSame($expected, $redis->recordedCommands);
    }

    public function testSetBounds(): void
    {
        $redis = new RedisStub();

        $command = new Set($redis, ['props', 'house1', Bounds::make(33.7840, - 112.1520, 33.7848, - 112.1512)]);
        $command->execute();

        self::assertSame(
            [['SET', 'props', 'house1', 'BOUNDS', '33.784', '-112.152', '33.7848', '-112.1512']],
            $redis->recordedCommands,
        );
    }

    public function testSetGeohash(): void
    {
        $redis = new RedisStub();

        $command = new Set($redis, ['props', 'area1', GeoHash::make('9tbnwg')]);
        $command->execute();

        self::assertSame(
            [['SET', 'props', 'area1', 'HASH', '9tbnwg']],
            $redis->recordedCommands,
        );
    }

    public function testGetWithOptions(): void
    {
        $redis = new RedisStub();

        $command = new Get($redis, ['fleet', 'truck1']);
        $command->withfields()->point();

        $command->execute();

        self::assertSame(
            [['GET', 'fleet', 'truck1', 'WITHFIELDS', 'POINT']],
            $redis->recordedCommands,
        );
    }

    public function testGetWithHashes(): void
    {
        $redis = new RedisStub();

        $command = new Get($redis, ['fleet', 'truck1']);
        $command->hashes(22);

        $command->execute();

        self::assertSame([['GET', 'fleet', 'truck1', 'HASH', '22']], $redis->recordedCommands);
    }

    public function testJgetRaw(): void
    {
        $redis = new RedisStub();

        $command = new Jget($redis, ['fleet', 'truck1', 'location']);
        $command->raw();

        $command->execute();

        self::assertSame(
            [['JGET', 'fleet', 'truck1', 'location', 'RAW']],
            $redis->recordedCommands,
        );
    }

    public function testSetRx(): void
    {
        $redis = new RedisStub();

        $command = new Set($redis, ['fleet', 'truck1', Point::make(33.5, - 112.3)]);
        $command->rx();

        $command->execute();

        self::assertSame(
            [['SET', 'fleet', 'truck1', 'RX', 'POINT', '33.5', '-112.3']],
            $redis->recordedCommands,
        );
    }

    public function testFsetRx(): void
    {
        $redis = new RedisStub();

        $command = new Fset($redis, ['fleet', 'truck1', 'speed', 90]);
        $command->rx();

        $command->execute();

        self::assertSame(
            [['FSET', 'fleet', 'truck1', 'speed', '90', 'RX']],
            $redis->recordedCommands,
        );
    }
}
