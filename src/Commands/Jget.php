<?php

declare(strict_types=1);

namespace Ronappleton\Tile38PhpClient\Commands;

use Ronappleton\Tile38PhpClient\Commands\Abstracts\Command;

class Jget extends Command
{
    protected string $command = 'JGET';

    protected int $argumentCountRequired = 3;
}
