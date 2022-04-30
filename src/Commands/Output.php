<?php

declare(strict_types=1);

namespace Ronappleton\Tile38PhpClient\Commands;

use Redis;
use Ronappleton\Tile38PhpClient\Clients\Tile38;
use Ronappleton\Tile38PhpClient\Exceptions\MissingArgument;
use Ronappleton\Tile38PhpClient\Commands\Abstracts\Command;

class Output extends Command
{
    protected string $command = 'OUTPUT';
    
    public function execute(): Redis|array|string|bool
    {
        if (!isset($this->arguments[0])) {
            throw new MissingArgument('resp|json');
        }
        
        return parent::execute();
    }
}
