<?php

declare(strict_types=1);

namespace Ronappleton\Tile38PhpClient\Commands;

use Ronappleton\Tile38PhpClient\Commands\Abstracts\Command;

class Info extends Command
{
    protected string $command = 'INFO';

    protected int $argumentCountRequired = 0;
}
