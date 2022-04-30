<?php

declare(strict_types=1);

namespace Ronappleton\Tile38PhpClient\Commands;

use Redis;
use Ronappleton\Tile38PhpClient\Clients\Tile38;
use Ronappleton\Tile38PhpClient\Commands\Abstracts\Command;

class Server extends Command
{
    protected string $command = 'SERVER';
    
    public function execute(): Redis|array|string|bool
    {
        $this->sendCommand('OUTPUT', 'json');
        
        return parent::execute();
    }
}
