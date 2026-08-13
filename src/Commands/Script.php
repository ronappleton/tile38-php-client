<?php

declare(strict_types=1);

namespace Ronappleton\Tile38PhpClient\Commands;

use Ronappleton\Tile38PhpClient\Commands\Abstracts\Command;

class Script extends Command
{
    protected string $command = 'SCRIPT';

    protected int $argumentCountRequired = 1;
}
