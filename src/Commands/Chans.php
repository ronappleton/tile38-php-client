<?php

declare(strict_types=1);

namespace Ronappleton\Tile38PhpClient\Commands;

use Ronappleton\Tile38PhpClient\Commands\Abstracts\Command;

class Chans extends Command
{
    protected string $command = 'CHANS';

    protected int $argumentCountRequired = 1;
}
