<?php

declare(strict_types=1);

namespace Ronappleton\Tile38PhpClient\Commands\Server;

use Ronappleton\Tile38PhpClient\Commands\Abstracts\Command;

class Role extends Command
{
    protected string $command = 'ROLE';

    protected int $argumentCountRequired = 0;
}
