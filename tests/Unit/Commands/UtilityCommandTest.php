<?php

declare(strict_types=1);

namespace Ronappleton\Tile38PhpClient\Tests\Unit\Commands;

use Ronappleton\Tile38PhpClient\Commands\Objects\Point;
use Ronappleton\Tile38PhpClient\Commands\Utility\Raw;
use Ronappleton\Tile38PhpClient\Tests\Support\RedisStub;

class UtilityCommandTest extends CommandTestCase
{
    protected const array COMMANDS = [
        'test' => [],
    ];

    public function testRawPassthrough(): void
    {
        $redis = new RedisStub();

        $command = new Raw($redis, ['SCAN', 'fleet', 'COUNT']);
        $command->execute();

        self::assertSame([['SCAN', 'fleet', 'COUNT']], $redis->recordedCommands);
    }

    public function testRawWithObjectArgument(): void
    {
        $redis = new RedisStub();

        $command = new Raw(
            $redis,
            ['SET', 'fleet', 'truck1', Point::make(33.5123, - 112.2693)],
        );
        $command->execute();

        self::assertSame(
            [['SET', 'fleet', 'truck1', 'POINT', '33.5123', '-112.2693']],
            $redis->recordedCommands,
        );
    }
}
