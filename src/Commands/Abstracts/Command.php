<?php

declare(strict_types=1);

namespace Ronappleton\Tile38PhpClient\Commands\Abstracts;

use Redis;
use Ronappleton\Tile38PhpClient\Commands\Interfaces\Command as CommandInterface;
use Ronappleton\Tile38PhpClient\Commands\Interfaces\CommandObject;
use Ronappleton\Tile38PhpClient\Exceptions\InvalidType;
use Ronappleton\Tile38PhpClient\Exceptions\ObjectNotCommandObject;
use Ronappleton\Tile38PhpClient\Exceptions\RequiredArgumentCount;

use function count;
use function gettype;

abstract class Command implements CommandInterface
{
    protected string $command = '';

    protected int $argumentCountRequired = 0;

    /**
     * @param array<int, mixed> $arguments
     */
    public function __construct(protected readonly Redis $client, protected array $arguments = []) {
        if (count($this->arguments) < $this->argumentCountRequired) {
            throw new RequiredArgumentCount($this->argumentCountRequired);
        }
    }

    public function execute(): mixed
    {
        return $this->client->rawCommand($this->getCommand(), ... $this->buildArguments());
    }

    /**
     * @param array<int, mixed> $arguments
     *
     * @return array<int, mixed>
     */
    public function formatArguments(array $arguments): array
    {
        $args = [];

        foreach ($arguments as $argument) {
            $args = [... $args, ... $this->formatArgument($argument)];
        }

        return $args;
    }

    public function output(string $output): static
    {
        $this->client->rawCommand('OUTPUT', $output);

        return $this;
    }

    /**
     * @return array<int, mixed>
     */
    protected function buildArguments(): array
    {
        return $this->formatArguments($this->arguments);
    }

    protected function getCommand(): string
    {
        return $this->command;
    }

    /**
     * @return array<int, mixed>
     */
    private function formatArgument(mixed $argument): array
    {
        return match (gettype($argument)) {
            'boolean', 'integer', 'double', 'string' => [(string) $argument],
            'array' => $this->formatArguments($argument),
            'object' => $this->objectArguments($argument),
            default => throw new InvalidType(gettype($argument)),
        };
    }

    /**
     * @return array<int, mixed>
     */
    private function objectArguments(object $argument): array
    {
        if (!$argument instanceof CommandObject) {
            throw new ObjectNotCommandObject($argument::class);
        }

        return $argument->toArguments();
    }
}
