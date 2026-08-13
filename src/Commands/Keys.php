<?php

declare(strict_types=1);

namespace Ronappleton\Tile38PhpClient\Commands;

use Ronappleton\Tile38PhpClient\Commands\Abstracts\Command;

class Keys extends Command
{
    protected string $command = 'KEYS';

    protected int $argumentCountRequired = 1;
}
