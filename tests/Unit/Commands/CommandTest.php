<?php

declare(strict_types=1);

namespace Ronappleton\Tile38PhpClient\Tests\Unit\Commands;

use PHPUnit\Framework\TestCase;
use Ronappleton\Tile38PhpClient\Commands\Objects\Point;
use Ronappleton\Tile38PhpClient\Commands\Connection\Ping;
use Ronappleton\Tile38PhpClient\Enums\SearchType;
use Ronappleton\Tile38PhpClient\Exceptions\InvalidType;
use Ronappleton\Tile38PhpClient\Exceptions\ObjectNotCommandObject;
use Ronappleton\Tile38PhpClient\Exceptions\RequiredArgumentCount;
use Ronappleton\Tile38PhpClient\Tests\Support\RedisStub;
use Ronappleton\Tile38PhpClient\Commands\Channel\Chans;
use stdClass;

class CommandTest extends TestCase
{
    public function testRequiredArgumentCountThrows(): void
    {
        $this->expectException(RequiredArgumentCount::class);

        new Chans(new RedisStub(), []);
    }

    public function testFormattingScalars(): void
    {
        $command = new Ping(new RedisStub());

        self::assertSame(
            ['1', '1', '42', '3.5', 'string'],
            $command->formatArguments([true, 1, 42, 3.5, 'string']),
        );
    }

    public function testFormattingArrayIsFlattened(): void
    {
        $command = new Ping(new RedisStub());

        self::assertSame(
            ['key', 'FIELD', 'speed', '90'],
            $command->formatArguments(['key', ['FIELD', 'speed', 90]]),
        );
    }

    public function testFormattingCommandObjectFlattensToTokens(): void
    {
        $command = new Ping(new RedisStub());

        self::assertSame(
            ['POINT', '33.5123', '-112.2693'],
            $command->formatArguments([Point::make(33.5123, - 112.2693)]),
        );
    }

    public function testFormattingBackedEnumFlattensToValue(): void
    {
        $command = new Ping(new RedisStub());

        self::assertSame(
            ['NEARBY'],
            $command->formatArguments([SearchType::Nearby]),
        );
    }

    public function testFormattingNonCommandObjectThrows(): void
    {
        $this->expectException(ObjectNotCommandObject::class);

        $command = new Ping(new RedisStub());
        $command->formatArguments([new stdClass()]);
    }

    public function testFormattingNullThrows(): void
    {
        $this->expectException(InvalidType::class);

        $command = new Ping(new RedisStub());
        $command->formatArguments([null]);
    }

    public function testOutputSendsOutputAndReturnsCommand(): void
    {
        $redis = new RedisStub();
        $command = new Ping($redis);

        $result = $command->output('json');

        self::assertSame($command, $result);
        self::assertSame([['OUTPUT', 'json']], $redis->recordedCommands);
    }
}
