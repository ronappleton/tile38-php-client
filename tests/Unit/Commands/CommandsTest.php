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
use Ronappleton\Tile38PhpClient\Commands\Within;
use Ronappleton\Tile38PhpClient\Exceptions\RequiredArgumentCount;
use Ronappleton\Tile38PhpClient\Tests\Support\RedisStub;

use function array_fill;
use function array_map;
use function ucfirst;

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
        'bounds' => ['BOUNDS', ['fleet', 'truck1']],
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
    ];

    /**
     * @return array<string, array{class: class-string<Command>, arguments: array<int, mixed>, expected: array<int, mixed>}>
     */
    public static function commandProvider(): array
    {
        $provider = [];

        foreach (self::COMMANDS as $name => [$command, $arguments]) {
            $provider[$name] = [
                'class' => 'Ronappleton\\Tile38PhpClient\\Commands\\' . ucfirst($name),
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
