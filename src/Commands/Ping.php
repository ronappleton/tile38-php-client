<?php

declare(strict_types=1);

namespace Ronappleton\Tile38PhpClient\Commands;

use Redis;

class Ping extends Abstracts\Command
{
    protected string $command = 'PING';
    
    public function execute(): Redis|array|string|bool
    {
        $this->sendCommand('OUTPUT', 'json');
        
        return parent::execute();
    }
}
