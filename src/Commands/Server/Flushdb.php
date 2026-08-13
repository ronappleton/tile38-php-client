<?php

declare(strict_types=1);

namespace Ronappleton\Tile38PhpClient\Commands\Server;

use Ronappleton\Tile38PhpClient\Commands\Abstracts\Command;

class Flushdb extends Command
{
    protected string $command = 'FLUSHDB';

    protected int $argumentCountRequired = 0;
}
