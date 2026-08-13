<?php

declare(strict_types=1);

namespace Ronappleton\Tile38PhpClient\Commands;

use Ronappleton\Tile38PhpClient\Commands\Abstracts\Command;

class Pdel extends Command
{
    protected string $command = 'PDEL';

    protected int $argumentCountRequired = 2;
}
