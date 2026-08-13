<?php

declare(strict_types=1);

namespace Ronappleton\Tile38PhpClient\Commands;

use Ronappleton\Tile38PhpClient\Commands\Abstracts\Command;

class ReadonlyMode extends Command
{
    protected string $command = 'READONLY';

    protected int $argumentCountRequired = 0;
}
