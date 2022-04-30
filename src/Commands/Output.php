<?php

declare(strict_types=1);

namespace Ronappleton\Tile38PhpClient\Commands;

use Redis;
use Ronappleton\Tile38PhpClient\Clients\Tile38;
use Ronappleton\Tile38PhpClient\Exceptions\MissingArgument;
use Ronappleton\Tile38PhpClient\Interfaces\Command;

class Output implements Command
{
    /**
     * @param array<int, mixed> $arguments
     */
    public function __construct(private readonly Tile38 $client, private readonly array $arguments = [])
    {
    }
    
    public function execute(): Redis|array|string|bool
    {
        if (!isset($this->arguments[0])) {
            throw new MissingArgument('resp|json');
        }
        return $this->client->rawCommand('OUTPUT', $this->arguments[0]);
    }
}
