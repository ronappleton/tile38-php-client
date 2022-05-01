<?php

declare(strict_types=1);

namespace Ronappleton\Tile38PhpClient\Commands;

use Redis;
use Ronappleton\Tile38PhpClient\Exceptions\MissingArgument;

class Stats extends Abstracts\Command
{
    protected string $command = 'STATS';
    
    public function execute(): Redis|array|string|bool
    {
        if (!isset($this->arguments[0])) {
            throw new MissingArgument('stats needs keys to check');
        }
        
        $this->sendCommand('OUTPUT', 'json');
        
        return parent::execute();
    }
}
