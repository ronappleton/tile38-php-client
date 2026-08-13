<?php

declare(strict_types=1);

namespace Ronappleton\Tile38PhpClient\Commands;

use Ronappleton\Tile38PhpClient\Commands\Abstracts\Command;

class Fexists extends Command
{
    protected string $command = 'FEXISTS';

    protected int $argumentCountRequired = 3;
}
