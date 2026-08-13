<?php

declare(strict_types=1);

namespace Ronappleton\Tile38PhpClient\Commands;

use Ronappleton\Tile38PhpClient\Commands\Abstracts\Command;

class Jset extends Command
{
    protected string $command = 'JSET';

    protected int $argumentCountRequired = 4;
}
