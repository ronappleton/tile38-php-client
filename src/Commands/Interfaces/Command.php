<?php

declare(strict_types=1);

namespace Ronappleton\Tile38PhpClient\Commands\Interfaces;

use Redis;

interface Command
{
    /**
     * @param array<int, mixed> $arguments
     */
    public function __construct(Redis $client, array $arguments = []);

    public function execute(): mixed;
}
