<?php

declare(strict_types=1);

namespace Ronappleton\Tile38PhpClient\Commands;

use Ronappleton\Tile38PhpClient\Commands\Abstracts\Command;

class Delchan extends Command
{
    protected string $command = 'DELCHAN';

    protected int $argumentCountRequired = 1;
}
