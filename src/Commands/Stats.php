<?php

declare(strict_types=1);

namespace Ronappleton\Tile38PhpClient\Commands;

use Ronappleton\Tile38PhpClient\Commands\Abstracts\Command;

class Stats extends Command
{
    protected string $command = 'STATS';

    protected int $argumentCountRequired = 1;
}
