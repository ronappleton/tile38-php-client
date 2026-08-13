<?php

declare(strict_types=1);

namespace Ronappleton\Tile38PhpClient\Clients;

use Redis;
use Ronappleton\Tile38PhpClient\Commands\CommandRegistry;
use Ronappleton\Tile38PhpClient\Exceptions\CommandDoesNotExist;
use Ronappleton\Tile38PhpClient\Exceptions\RequiredArgumentCount;

use function count;
use function strtolower;

class Tile38
{
    private readonly Redis $client;

    public function __construct(
        string $host,
        int $port = 9851,
        ?string $password = null,
        float $timeout = 0.0,
        mixed $reserved = null,
        int $retryInterval = 0,
        float $readTimeout = 0.0,
        ?Redis $client = null,
    ) {
        $this->client = $client ?? new Redis();

        if ($client !== null) {
            return;
        }

        $this->client->connect($host, $port, $timeout, $reserved, $retryInterval, $readTimeout);

        if (!$password) {
            return;
        }

        $this->client->auth($password);
    }

    /**
     * @param array<int, mixed> $arguments
     */
    private function setOutput(array $arguments): self
    {
        if (!count($arguments)) {
            throw new RequiredArgumentCount(1);
        }

        $this->client->rawCommand('OUTPUT', $arguments[0]);

        return $this;
    }

    /**
     * @param array<int, mixed> $arguments
     */
    public function __call(string $command, array $arguments = []): mixed
    {
        $command = strtolower($command);

        if ($command === 'output') {
            return $this->setOutput($arguments);
        }

        $class = CommandRegistry::COMMANDS[$command] ?? throw new CommandDoesNotExist($command);

        return new $class($this->client, $arguments);
    }
}
