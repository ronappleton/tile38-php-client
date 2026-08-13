<?php

declare(strict_types=1);

namespace Ronappleton\Tile38PhpClient\Commands;

use Ronappleton\Tile38PhpClient\Commands\Abstracts\Command;

class Aofshrink extends Command
{
    protected string $command = 'AOFSHRINK';

    protected int $argumentCountRequired = 0;
}
