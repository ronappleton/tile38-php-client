<?php

declare(strict_types=1);

namespace Ronappleton\Tile38PhpClient\Tests\Unit\Commands;

use Ronappleton\Tile38PhpClient\Commands\Channel\Setchan;
use Ronappleton\Tile38PhpClient\Commands\Objects\Bounds;
use Ronappleton\Tile38PhpClient\Commands\Objects\Point;
use Ronappleton\Tile38PhpClient\Enums\SearchType;
use Ronappleton\Tile38PhpClient\Tests\Support\RedisStub;

class ChannelCommandTest extends CommandTestCase
{
    protected const array COMMANDS = [
        'chans' => ['*'],
        'delchan' => ['warehouse'],
        'pdelchan' => ['ware*'],
        'subscribe' => ['warehouse'],
        'psubscribe' => ['ware*'],
    ];

    public function testSetchanWithGeofence(): void
    {
        $redis = new RedisStub();

        $command = new Setchan($redis, ['warehouse', SearchType::Nearby, 'fleet', Point::make(33.5, - 112.3, 500)]);
        $command->where('speed', 70, '+inf')->fence()->detect('enter,exit');

        $command->execute();

        self::assertSame(
            [[
                'SETCHAN', 'warehouse', 'NEARBY', 'fleet', 'WHERE', 'speed', '70',
                '+inf', 'FENCE', 'DETECT', 'enter,exit', 'POINT', '33.5', '-112.3', '500',
            ]],
            $redis->recordedCommands,
        );
    }

    public function testSetchanWithMetaAndExpiry(): void
    {
        $redis = new RedisStub();

        $command = new Setchan(
            $redis,
            ['warehouse', SearchType::Within, 'fleet', Bounds::make(33.462, - 112.268, 33.491, - 112.245)],
        );
        $command->meta('type', 'highway')->ex(60)->fence();

        $command->execute();

        self::assertSame(
            [[
                'SETCHAN', 'warehouse', 'META', 'type', 'highway', 'EX', '60', 'WITHIN',
                'fleet', 'FENCE', 'BOUNDS', '33.462', '-112.268', '33.491', '-112.245',
            ]],
            $redis->recordedCommands,
        );
    }
}
