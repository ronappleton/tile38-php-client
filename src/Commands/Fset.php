<?php

declare(strict_types=1);

namespace Ronappleton\Tile38PhpClient\Commands;

use Ronappleton\Tile38PhpClient\Commands\Abstracts\Command;

class Fset extends Command
{
    protected string $command = 'FSET';

    protected int $argumentCountRequired = 4;
}
