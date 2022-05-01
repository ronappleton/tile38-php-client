<?php

declare(strict_types=1);

namespace Ronappleton\Tile38PhpClient\Commands;

use Redis;
use Ronappleton\Tile38PhpClient\Commands\Abstracts\Command;

class Quit extends Command
{
    protected string $command = 'QUIT';
    
    public function execute(): Redis|array|string
    {
        return parent::execute()
            ? '{"status":"ok", "message": "connection closed"}'
            : '{"status":false, "err": "could not close connection"}';
    }
}
