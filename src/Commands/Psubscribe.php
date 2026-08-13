<?php

declare(strict_types=1);

namespace Ronappleton\Tile38PhpClient\Commands;

use Ronappleton\Tile38PhpClient\Commands\Abstracts\Command;

class Psubscribe extends Command
{
    protected string $command = 'PSUBSCRIBE';

    protected int $argumentCountRequired = 1;
}
