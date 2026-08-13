<?php

declare(strict_types=1);

namespace Ronappleton\Tile38PhpClient\Tests\Support;

use function class_alias;
use function class_exists;

class RedisStub
{
    /**
     * @var array<int, array<int, mixed>>
     */
    public array $recordedCommands = [];

    public mixed $response = true;

    /**
     * @param array<int, mixed> $arguments
     */
    public function connect(... $arguments): bool
    {
        return true;
    }

    public function auth(string $password): bool
    {
        return true;
    }

    public function rawCommand(string $command, mixed ... $arguments): mixed
    {
        $this->recordedCommands[] = [$command, ... $arguments];

        return $this->response;
    }
}

if (!class_exists('Redis')) {
    class_alias(RedisStub::class, 'Redis');
}
