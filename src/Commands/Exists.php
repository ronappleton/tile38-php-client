<?php

declare(strict_types=1);

namespace Ronappleton\Tile38PhpClient\Commands;

use Ronappleton\Tile38PhpClient\Commands\Abstracts\Command;

class Exists extends Command
{
    protected string $command = 'EXISTS';

    protected int $argumentCountRequired = 2;
}
