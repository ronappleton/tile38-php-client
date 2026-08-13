<?php

declare(strict_types=1);

namespace Ronappleton\Tile38PhpClient\Tests\Unit\Commands;

use Ronappleton\Tile38PhpClient\Commands\Objects\Point;
use Ronappleton\Tile38PhpClient\Commands\Webhook\Sethook;
use Ronappleton\Tile38PhpClient\Enums\SearchType;
use Ronappleton\Tile38PhpClient\Tests\Support\RedisStub;

class WebhookCommandTest extends CommandTestCase
{
    protected const array COMMANDS = [
        'delhook' => ['warehouse'],
        'pdelhook' => ['ware*'],
        'hooks' => ['ware*'],
    ];

    public function testSethookWithGeofence(): void
    {
        $redis = new RedisStub();

        $command = new Sethook(
            $redis,
            ['warehouse', 'http://example.com/hook', SearchType::Nearby, 'fleet', Point::make(33.5, - 112.3, 500)],
        );
        $command->fence()->commands('all');

        $command->execute();

        self::assertSame(
            [[
                'SETHOOK', 'warehouse', 'http://example.com/hook', 'NEARBY', 'fleet', 'FENCE',
                'COMMANDS', 'all', 'POINT', '33.5', '-112.3', '500',
            ]],
            $redis->recordedCommands,
        );
    }
}
