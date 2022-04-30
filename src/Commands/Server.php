<?php

declare(strict_types=1);

namespace Ronappleton\Tile38PhpClient\Commands;

use Redis;
use Ronappleton\Tile38PhpClient\Clients\Tile38;
use Ronappleton\Tile38PhpClient\Commands\Abstracts\Command;

class Server extends Command
{
    /**
     * @param array<int, mixed> $arguments
     */
    public function __construct(private readonly Tile38 $client, private readonly array $arguments = [])
    {
    }
    
    public function execute(): Redis|array|string|bool
    {
        $this->client->command->output('resp');
        
        return $this->client->info('SERVER');
    }
}
