<?php

declare(strict_types=1);

namespace Ronappleton\Tile38PhpClient\Tests\Unit\Commands;

use PHPUnit\Framework\TestCase;
use Ronappleton\Tile38PhpClient\Commands\CommandRegistry;
use Ronappleton\Tile38PhpClient\Commands\Interfaces\Command;
use Ronappleton\Tile38PhpClient\Tests\Support\RedisStub;

use function array_map;
use function strtoupper;

abstract class CommandTestCase extends TestCase
{
    /**
     * Builds a data provider from the subclass's `COMMANDS` constant. The command
     * class is resolved through the registry and the expected wire format derived
     * from the command name, ensuring the map and registry stay in sync.
     *
     * @return array<string, array{class: class-string<Command>, arguments: array<int, mixed>, expected: array<int, mixed>}>
     */
    public static function commandProvider(): array
    {
        // phpcs:ignore SlevomatCodingStandard.Classes.DisallowLateStaticBindingForConstants
        return self::providerFrom(static::COMMANDS);
    }

    /**
     * @param array<string, array<int, mixed>> $commands
     *
     * @return array<string, array{class: class-string<Command>, arguments: array<int, mixed>, expected: array<int, mixed>}>
     */
    protected static function providerFrom(array $commands): array
    {
        $provider = [];

        foreach ($commands as $name => $arguments) {
            $provider[$name] = [
                'class' => CommandRegistry::COMMANDS[$name],
                'arguments' => $arguments,
                'expected' => [[
                    strtoupper($name),
                    ... array_map(static fn (mixed $value): string => (string) $value, $arguments),
                ]],
            ];
        }

        return $provider;
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
}
