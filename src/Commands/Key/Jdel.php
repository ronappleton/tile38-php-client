<?php

declare(strict_types=1);

namespace Ronappleton\Tile38PhpClient\Commands\Key;

use Ronappleton\Tile38PhpClient\Commands\Abstracts\Command;

class Jdel extends Command
{
    protected string $command = 'JDEL';

    protected int $argumentCountRequired = 3;
}
