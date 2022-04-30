<?php

declare(strict_types=1);

namespace Ronappleton\Tile38PhpClient\Commands;

use Redis;
use Ronappleton\Tile38PhpClient\Clients\Tile38;
use Ronappleton\Tile38PhpClient\Exceptions\MissingArgument;
use Ronappleton\Tile38PhpClient\Commands\Abstracts\Command as AbstractCommand;

class Ttl extends AbstractCommand
{
    protected string $command = 'TTL';
    
    public function execute(): Redis|array|string|bool
    {
        if (!isset($this->arguments[0])) {
            throw new MissingArgument('key');
        }
        
        if (!isset($this->arguments[1])) {
            throw new MissingArgument('id');
        }
        
        $this->sendCommand('OUTPUT', 'json');

        return parent::execute();
    }
}
