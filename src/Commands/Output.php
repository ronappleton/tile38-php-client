<?php

declare(strict_types=1);

namespace Ronappleton\Tile38PhpClient\Commands;

use Ronappleton\Tile38PhpClient\Commands\Abstracts\Command;

class Output extends Command
{
    protected string $command = 'OUTPUT';
    
    protected int $argumentCountRequired = 1;
    
    public function execute(): mixed
    {
        parent::execute();
        
        return null;
    }
}
