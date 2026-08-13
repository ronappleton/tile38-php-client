<?php

declare(strict_types=1);

namespace Ronappleton\Tile38PhpClient\Commands;

use Ronappleton\Tile38PhpClient\Commands\Abstracts\Command;

class Follow extends Command
{
    protected string $command = 'FOLLOW';

    protected int $argumentCountRequired = 1;
}
