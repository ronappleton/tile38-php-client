<?php

declare(strict_types=1);

namespace Ronappleton\Tile38PhpClient\Commands;

use Ronappleton\Tile38PhpClient\Commands\Abstracts\Command;

class Evalna extends Command
{
    protected string $command = 'EVALNA';

    protected int $argumentCountRequired = 2;
}
