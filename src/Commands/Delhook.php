<?php

declare(strict_types=1);

namespace Ronappleton\Tile38PhpClient\Commands;

use Ronappleton\Tile38PhpClient\Commands\Abstracts\Command;

class Delhook extends Command
{
    protected string $command = 'DELHOOK';

    protected int $argumentCountRequired = 1;
}
