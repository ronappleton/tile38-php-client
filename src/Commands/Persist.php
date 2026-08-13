<?php

declare(strict_types=1);

namespace Ronappleton\Tile38PhpClient\Commands;

use Ronappleton\Tile38PhpClient\Commands\Abstracts\Command;

class Persist extends Command
{
    protected string $command = 'PERSIST';

    protected int $argumentCountRequired = 2;
}
