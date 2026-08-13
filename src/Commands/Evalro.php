<?php

declare(strict_types=1);

namespace Ronappleton\Tile38PhpClient\Commands;

use Ronappleton\Tile38PhpClient\Commands\Abstracts\Command;

class Evalro extends Command
{
    protected string $command = 'EVALRO';

    protected int $argumentCountRequired = 2;
}
