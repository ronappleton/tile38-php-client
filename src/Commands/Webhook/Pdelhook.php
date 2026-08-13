<?php

declare(strict_types=1);

namespace Ronappleton\Tile38PhpClient\Commands\Webhook;

use Ronappleton\Tile38PhpClient\Commands\Abstracts\Command;

class Pdelhook extends Command
{
    protected string $command = 'PDELHOOK';

    protected int $argumentCountRequired = 1;
}
