<?php

declare(strict_types=1);

namespace Ronappleton\Tile38PhpClient\Commands;

use Ronappleton\Tile38PhpClient\Commands\Abstracts\Command;

class Expire extends Command
{
    protected string $command = 'EXPIRE';

    protected int $argumentCountRequired = 3;
}
