<?php

declare(strict_types=1);

namespace Ronappleton\Tile38PhpClient\Commands;

use Redis;
use Ronappleton\Tile38PhpClient\Clients\Tile38;

class Quit implements \Ronappleton\Tile38PhpClient\Interfaces\Command
{
    public function __construct(private readonly Tile38 $client, private readonly array $arguments = [])
    {
    }

    public function execute(): Redis|array|string
    {
        return $this->client->rawCommand('QUIT', '')
            ? 'connection closed'
            : 'could not close connection';
    }
}
