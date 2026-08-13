<?php

declare(strict_types=1);

namespace Ronappleton\Tile38PhpClient\Commands\Scripting;

use Ronappleton\Tile38PhpClient\Commands\Abstracts\Command;

class Evalsha extends Command
{
    protected string $command = 'EVALSHA';

    protected int $argumentCountRequired = 2;
}
