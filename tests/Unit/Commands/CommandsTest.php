<?php

declare(strict_types=1);

namespace Ronappleton\Tile38PhpClient\Tests\Unit\Commands;

use PHPUnit\Framework\TestCase;
use ReflectionClass;
use Ronappleton\Tile38PhpClient\Commands\Abstracts\Command;
use Ronappleton\Tile38PhpClient\Commands\Chans;
use Ronappleton\Tile38PhpClient\Commands\Del;
use Ronappleton\Tile38PhpClient\Commands\Fset;
use Ronappleton\Tile38PhpClient\Commands\Intersects;
use Ronappleton\Tile38PhpClient\Commands\Nearby;
use Ronappleton\Tile38PhpClient\Commands\Objects\Bounds;
use Ronappleton\Tile38PhpClient\Commands\Objects\Circle;
use Ronappleton\Tile38PhpClient\Commands\Objects\GeoHash;
use Ronappleton\Tile38PhpClient\Commands\Objects\Point;
use Ronappleton\Tile38PhpClient\Commands\Raw;
use Ronappleton\Tile38PhpClient\Commands\Scan;
use Ronappleton\Tile38PhpClient\Commands\Search;
use Ronappleton\Tile38PhpClient\Commands\Set;
use Ronappleton\Tile38PhpClient\Commands\Setchan;
use Ronappleton\Tile38PhpClient\Commands\Sethook;
use Ronappleton\Tile38PhpClient\Commands\Within;
use Ronappleton\Tile38PhpClient\Enums\SearchType;
use Ronappleton\Tile38PhpClient\Exceptions\RequiredArgumentCount;
use Ronappleton\Tile38PhpClient\Tests\Support\RedisStub;

use function array_fill;
use function array_map;
use function ucfirst;

use Ronappleton\Tile38PhpClient\Commands\EvalScript;
use Ronappleton\Tile38PhpClient\Commands\Get;
use Ronappleton\Tile38PhpClient\Commands\Jget;
use Ronappleton\Tile38PhpClient\Commands\Objects\Roam;

class CommandsTest extends TestCase
{
    private const array COMMANDS = [
        'auth' => ['AUTH', ['secret']],
        'chans' => ['CHANS', ['*']],
        'output' => ['OUTPUT', ['json']],
        'ping' => ['PING', []],
        'quit' => ['QUIT', []],
        'server' => ['SERVER', []],
        'stats' => ['STATS', ['fleet']],
        'ttl' => ['TTL', ['fleet', 'truck1']],
            'bounds' => ['BOUNDS', ['fleet']],
        'del' => ['DEL', ['fleet', 'truck1']],
        'drop' => ['DROP', ['fleet']],
        'expire' => ['EXPIRE', ['fleet', 'truck1', 60]],
        'persist' => ['PERSIST', ['fleet', 'truck1']],
        'keys' => ['KEYS', ['*']],
        'rename' => ['RENAME', ['fleet', 'fleet2']],
        'renamenx' => ['RENAMENX', ['fleet', 'fleet2']],
        'get' => ['GET', ['fleet', 'truck1']],
        'fset' => ['FSET', ['fleet', 'truck1', 'speed', 90]],
        'jset' => ['JSET', ['fleet', 'truck1', 'location.lat', 33.5]],
        'jget' => ['JGET', ['fleet', 'truck1', 'location']],
        'jdel' => ['JDEL', ['fleet', 'truck1', 'location']],
        'pdel' => ['PDEL', ['fleet', 'truck*']],
        'delchan' => ['DELCHAN', ['warehouse']],
        'pdelchan' => ['PDELCHAN', ['ware*']],
        'subscribe' => ['SUBSCRIBE', ['warehouse']],
        'psubscribe' => ['PSUBSCRIBE', ['ware*']],
        'config' => ['CONFIG', ['get', 'requirepass']],
        'flushdb' => ['FLUSHDB', []],
        'follow' => ['FOLLOW', ['leader', 9851]],
        'gc' => ['GC', []],
        'readonly' => ['READONLY', []],
        'healthz' => ['HEALTHZ', []],
        'info' => ['INFO', []],
        'role' => ['ROLE', []],
        'aof' => ['AOF', []],
        'aofmd5' => ['AOFMD5', [100, 64]],
        'aofshrink' => ['AOFSHRINK', []],
        'exists' => ['EXISTS', ['fleet', 'truck1']],
        'fexists' => ['FEXISTS', ['fleet', 'truck1', 'speed']],
        'fget' => ['FGET', ['fleet', 'truck1', 'speed']],
        'eval' => ['EVAL', ['return KEYS[1]', 1, 'mykey']],
        'evalsha' => ['EVALSHA', ['d8bc1591', 0]],
        'evalna' => ['EVALNA', ['return 1', 0]],
        'evalnasha' => ['EVALNASHA', ['d8bc1591', 0]],
        'evalro' => ['EVALRO', ['return 1', 0]],
        'evalrosha' => ['EVALROSHA', ['d8bc1591', 0]],
        'script' => ['SCRIPT', ['flush']],
        'delhook' => ['DELHOOK', ['warehouse']],
        'pdelhook' => ['PDELHOOK', ['ware*']],
        'hooks' => ['HOOKS', ['ware*']],
        'test' => ['TEST', []],
    ];

    /**
     * @return array<string, array{class: class-string<Command>, arguments: array<int, mixed>, expected: array<int, mixed>}>
     */
    public static function commandProvider(): array
    {
        $provider = [];

        foreach (self::COMMANDS as $name => [$command, $arguments]) {
            $className = match ($name) {
                'eval' => 'EvalScript',
                'readonly' => 'ReadonlyMode',
                default => ucfirst($name),
            };

            $provider[$name] = [
                'class' => 'Ronappleton\\Tile38PhpClient\\Commands\\' . $className,
                'arguments' => $arguments,
                'expected' => [[$command, ... array_map(
                    static fn (mixed $value): string => (string) $value,
                    $arguments,
                )]],
            ];
        }

        return $provider;
    }

    /**
     * @return array<string, array<int, mixed>>
     */
    public static function requiredArgumentProvider(): array
    {
        return [
            'chans needs pattern' => [Chans::class, []],
            'del needs key and id' => [Del::class, ['fleet']],
            'set needs key, id and object' => [Set::class, ['fleet', 'truck1']],
            'nearby needs key and area' => [Nearby::class, ['fleet']],
            'fset needs four arguments' => [Fset::class, ['fleet', 'truck1', 'speed']],
            'setchan needs name, type, key and area' => [Setchan::class, ['warehouse', 'NEARBY', 'fleet']],
            'sethook needs name, endpoint, type, key and area' => [
                Sethook::class,
                ['warehouse', 'http://example.com', 'NEARBY', 'fleet'],
            ],
            'eval needs script and numkeys' => [EvalScript::class, ['return 1']],
        ];
    }

    /**
     * @return array<string, array<int, string>>
     */
    public static function fluentReturnProvider(): array
    {
        return [
            'scan' => [Scan::class],
            'search' => [Search::class],
            'nearby' => [Nearby::class],
            'within' => [Within::class],
            'intersects' => [Intersects::class],
        ];
    }

    /**
     * @param class-string<Command> $class
     * @param array<int, mixed> $arguments
     * @param array<int, array<int, mixed>> $expected
     *
     * @dataProvider commandProvider
     */
    public function testCommandExecution(string $class, array $arguments, array $expected): void
    {
        $redis = new RedisStub();
        $command = new $class($redis, $arguments);

        $command->execute();

        self::assertSame($expected, $redis->recordedCommands);
    }

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

    /**
     * @param class-string<Command> $class
     * @param array<int, mixed> $arguments
     *
     * @dataProvider requiredArgumentProvider
     */
    public function testRequiredArgumentCounts(string $class, array $arguments): void
    {
        $this->expectException(RequiredArgumentCount::class);

        new $class(new RedisStub(), $arguments);
    }

    /**
     * @param class-string<Command> $class
     *
     * @dataProvider fluentReturnProvider
     */
    public function testFluentMethodsReturnCommandInstance(string $class): void
    {
        $redis = new RedisStub();

        $reflection = new ReflectionClass($class);
        $required = $reflection->getProperty('argumentCountRequired')->getDefaultValue();
        $arguments = array_fill(0, $required, 'placeholder');

        /** @var Command $command */
        $command = new $class($redis, $arguments);

        $result = $command->count();

        self::assertSame($command, $result);
    }
}
