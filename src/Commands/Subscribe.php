<?php

declare(strict_types=1);

namespace Ronappleton\Tile38PhpClient\Commands;

use Ronappleton\Tile38PhpClient\Commands\Abstracts\Command;

class Subscribe extends Command
{
    protected string $command = 'SUBSCRIBE';

    protected int $argumentCountRequired = 1;
}
