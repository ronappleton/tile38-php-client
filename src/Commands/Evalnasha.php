<?php

declare(strict_types=1);

namespace Ronappleton\Tile38PhpClient\Commands;

use Ronappleton\Tile38PhpClient\Commands\Abstracts\Command;

class Evalnasha extends Command
{
    protected string $command = 'EVALNASHA';

    protected int $argumentCountRequired = 2;
}
