<?php

declare(strict_types=1);

namespace Ronappleton\Tile38PhpClient\Commands\Scripting;

use Ronappleton\Tile38PhpClient\Commands\Abstracts\Command;

class Evalrosha extends Command
{
    protected string $command = 'EVALROSHA';

    protected int $argumentCountRequired = 2;
}
