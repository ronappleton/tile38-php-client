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
}
