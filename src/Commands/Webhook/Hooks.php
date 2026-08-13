<?php

declare(strict_types=1);

namespace Ronappleton\Tile38PhpClient\Commands\Webhook;

use Ronappleton\Tile38PhpClient\Commands\Abstracts\Command;

class Hooks extends Command
{
    protected string $command = 'HOOKS';

    protected int $argumentCountRequired = 1;
}
