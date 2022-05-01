<?php

declare(strict_types=1);

namespace Ronappleton\Tile38PhpClient\Commands\Interfaces;

use Redis;
use Ronappleton\Tile38PhpClient\Clients\Tile38;

interface Command
{
    /**
     * @param array<int, mixed> $arguments
     */
    public function __construct(
        Tile38 $client,
        array $arguments = [],
        float $timeout = 0.0,
        string $outputType = 'json',
    );

    public function execute(): Redis|array|string|bool;
}
