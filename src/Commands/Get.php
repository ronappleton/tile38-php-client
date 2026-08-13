<?php

declare(strict_types=1);

namespace Ronappleton\Tile38PhpClient\Commands;

use Ronappleton\Tile38PhpClient\Commands\Abstracts\Command;

class Get extends Command
{
    protected string $command = 'GET';

    protected int $argumentCountRequired = 2;
}
