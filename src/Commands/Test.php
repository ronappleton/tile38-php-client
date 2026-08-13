<?php

declare(strict_types=1);

namespace Ronappleton\Tile38PhpClient\Commands;

use Ronappleton\Tile38PhpClient\Commands\Abstracts\Command;

class Test extends Command
{
    protected string $command = 'TEST';

    protected int $argumentCountRequired = 0;
}
