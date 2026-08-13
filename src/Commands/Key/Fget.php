<?php

declare(strict_types=1);

namespace Ronappleton\Tile38PhpClient\Commands\Key;

use Ronappleton\Tile38PhpClient\Commands\Abstracts\Command;

class Fget extends Command
{
    protected string $command = 'FGET';

    protected int $argumentCountRequired = 3;
}
