<?php

declare(strict_types=1);

namespace Ronappleton\Tile38PhpClient\Commands\Connection;

use Ronappleton\Tile38PhpClient\Commands\Abstracts\Command;

class Ping extends Command
{
    protected string $command = 'PING';
}
