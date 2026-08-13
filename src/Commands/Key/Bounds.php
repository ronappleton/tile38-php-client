<?php

declare(strict_types=1);

namespace Ronappleton\Tile38PhpClient\Commands\Key;

use Ronappleton\Tile38PhpClient\Commands\Abstracts\Command;

class Bounds extends Command
{
    protected string $command = 'BOUNDS';

    protected int $argumentCountRequired = 1;
}
