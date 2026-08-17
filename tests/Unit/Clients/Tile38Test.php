<?php

declare(strict_types=1);

namespace Ronappleton\Tile38PhpClient\Tests\Unit\Clients;

use PHPUnit\Framework\TestCase;
use Ronappleton\Tile38PhpClient\Clients\Tile38;
use Ronappleton\Tile38PhpClient\Commands\Abstracts\Command;
use Ronappleton\Tile38PhpClient\Commands\Channel\Chans;
use Ronappleton\Tile38PhpClient\Commands\Key\Set;
use Ronappleton\Tile38PhpClient\Exceptions\CommandDoesNotExist;
use Ronappleton\Tile38PhpClient\Exceptions\RequiredArgumentCount;
use Ronappleton\Tile38PhpClient\Tests\Support\RedisStub;
use Ronappleton\Tile38PhpClient\Commands\Key\Renamenx;
use RuntimeException;

class Tile38Test extends TestCase
{
    public function testDispatchingUnknownCommandThrows(): void
    {
        $this->expectException(CommandDoesNotExist::class);

        $client = new Tile38('127.0.0.1', 9851, null, 0.0, null, 0, 0.0, new RedisStub());
        $client->doesNotExist();
    }

    public function testDispatchReturnsCommandInstance(): void
    {
        $client = new Tile38('127.0.0.1', 9851, null, 0.0, null, 0, 0.0, new RedisStub());

        $command = $client->chans('*');

        self::assertInstanceOf(Chans::class, $command);
        self::assertInstanceOf(Command::class, $command);
    }

    public function testDispatchSetReturnsSetCommand(): void
    {
        $client = new Tile38('127.0.0.1', 9851, null, 0.0, null, 0, 0.0, new RedisStub());

        $command = $client->set('fleet', 'truck1', ['POINT', '33.5123', '-112.2693']);

        self::assertInstanceOf(Set::class, $command);
    }

    public function testDispatchIsCaseInsensitive(): void
    {
        $client = new Tile38('127.0.0.1', 9851, null, 0.0, null, 0, 0.0, new RedisStub());

        $command = $client->renameNx('fleet', 'fleet2');

        self::assertInstanceOf(Renamenx::class, $command);
    }

    public function testOutputSendsRawOutputCommand(): void
    {
        $redis = new RedisStub();
        $client = new Tile38('127.0.0.1', 9851, null, 0.0, null, 0, 0.0, $redis);

        $result = $client->output('json');

        self::assertSame($client, $result);
        self::assertSame([['OUTPUT', 'json']], $redis->recordedCommands);
    }

    public function testOutputWithoutArgumentThrows(): void
    {
        $this->expectException(RequiredArgumentCount::class);

        $client = new Tile38('127.0.0.1', 9851, null, 0.0, null, 0, 0.0, new RedisStub());
        $client->output();
    }

    public function testPipelineQueuesCommandsAndReturnsResults(): void
    {
        $redis = new RedisStub();
        $client = new Tile38('127.0.0.1', 9851, null, 0.0, null, 0, 0.0, $redis);

        $results = $client->pipeline(static function (Tile38 $client): void {
            $client->set('stores', 'store-1', ['POINT', '51.5', '-0.1'])->execute();
            $client->set('stores', 'store-2', ['POINT', '51.6', '-0.2'])->execute();
        });

        self::assertSame([], $results);
        self::assertSame(1, $redis->pipelineCount);
        self::assertSame(1, $redis->execCount);
        self::assertSame(0, $redis->discardCount);
        self::assertCount(2, $redis->recordedCommands);
    }

    public function testPipelineDiscardsWhenCallbackFails(): void
    {
        $redis = new RedisStub();
        $client = new Tile38('127.0.0.1', 9851, null, 0.0, null, 0, 0.0, $redis);

        $this->expectException(RuntimeException::class);

        try {
            $client->pipeline(static function (): void {
                throw new RuntimeException('pipeline failed');
            });
        } finally {
            self::assertSame(1, $redis->pipelineCount);
            self::assertSame(0, $redis->execCount);
            self::assertSame(1, $redis->discardCount);
        }
    }
}
