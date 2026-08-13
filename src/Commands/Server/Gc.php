<?php

declare(strict_types=1);

namespace Ronappleton\Tile38PhpClient\Commands\Server;

use Ronappleton\Tile38PhpClient\Commands\Abstracts\Command;

class Gc extends Command
{
    protected string $command = 'GC';

    protected int $argumentCountRequired = 0;
}
