<?php

declare(strict_types=1);

namespace Ronappleton\Tile38PhpClient\Commands\Channel;

use Ronappleton\Tile38PhpClient\Commands\Abstracts\Command;

class Pdelchan extends Command
{
    protected string $command = 'PDELCHAN';

    protected int $argumentCountRequired = 1;
}
