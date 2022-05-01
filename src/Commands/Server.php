<?php

declare(strict_types=1);

namespace Ronappleton\Tile38PhpClient\Commands;

use Ronappleton\Tile38PhpClient\Commands\Abstracts\Command;

class Server extends Command
{
    protected string $command = 'SERVER';
    
    protected int $argumentCountRequired = 1;
}
