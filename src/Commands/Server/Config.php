<?php

declare(strict_types=1);

namespace Ronappleton\Tile38PhpClient\Commands\Server;

use Ronappleton\Tile38PhpClient\Commands\Abstracts\Command;

class Config extends Command
{
    protected string $command = 'CONFIG';

    protected int $argumentCountRequired = 1;
}
