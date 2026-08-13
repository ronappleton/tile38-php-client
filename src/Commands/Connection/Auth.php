<?php

declare(strict_types=1);

namespace Ronappleton\Tile38PhpClient\Commands\Connection;

use Ronappleton\Tile38PhpClient\Commands\Abstracts\Command;

class Auth extends Command
{
    protected string $command = 'AUTH';
    
    protected int $argumentCountRequired = 1;
}
