<?php

declare(strict_types=1);

namespace Ronappleton\Tile38PhpClient\Commands\Key;

use Ronappleton\Tile38PhpClient\Commands\Abstracts\Command;

class Rename extends Command
{
    protected string $command = 'RENAME';

    protected int $argumentCountRequired = 2;
}
