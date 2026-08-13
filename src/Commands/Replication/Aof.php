<?php

declare(strict_types=1);

namespace Ronappleton\Tile38PhpClient\Commands\Replication;

use Ronappleton\Tile38PhpClient\Commands\Abstracts\Command;

class Aof extends Command
{
    protected string $command = 'AOF';

    protected int $argumentCountRequired = 0;
}
