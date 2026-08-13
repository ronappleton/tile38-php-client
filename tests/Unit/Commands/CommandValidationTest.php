<?php

declare(strict_types=1);

namespace Ronappleton\Tile38PhpClient\Tests\Unit\Commands;

use PHPUnit\Framework\TestCase;
use ReflectionClass;
use Ronappleton\Tile38PhpClient\Commands\Channel\Chans;
use Ronappleton\Tile38PhpClient\Commands\Channel\Setchan;
use Ronappleton\Tile38PhpClient\Commands\Interfaces\Command;
use Ronappleton\Tile38PhpClient\Commands\Key\Del;
use Ronappleton\Tile38PhpClient\Commands\Key\Fset;
use Ronappleton\Tile38PhpClient\Commands\Key\Set;
use Ronappleton\Tile38PhpClient\Commands\Scripting\EvalScript;
use Ronappleton\Tile38PhpClient\Commands\Search\Intersects;
use Ronappleton\Tile38PhpClient\Commands\Search\Nearby;
use Ronappleton\Tile38PhpClient\Commands\Search\Scan;
use Ronappleton\Tile38PhpClient\Commands\Search\Search;
use Ronappleton\Tile38PhpClient\Commands\Search\Within;
use Ronappleton\Tile38PhpClient\Commands\Webhook\Sethook;
use Ronappleton\Tile38PhpClient\Exceptions\RequiredArgumentCount;
use Ronappleton\Tile38PhpClient\Tests\Support\RedisStub;

use function array_fill;

class CommandValidationTest extends TestCase
{
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
