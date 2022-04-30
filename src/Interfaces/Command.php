<?php

declare(strict_types=1);

namespace Ronappleton\Tile38PhpClient\Interfaces;

use Redis;
use Ronappleton\Tile38PhpClient\Clients\Tile38;

interface Command
{
    /**
     * @param array<int, mixed> $arguments
     */
    public function __construct(Tile38 $client, array $arguments = []);

    public function execute(): Redis|array|string|bool;
}
