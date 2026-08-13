<?php

declare(strict_types=1);

namespace Ronappleton\Tile38PhpClient\Commands;

use Ronappleton\Tile38PhpClient\Commands\Abstracts\Command;

class Drop extends Command
{
    protected string $command = 'DROP';

    protected int $argumentCountRequired = 1;
}
