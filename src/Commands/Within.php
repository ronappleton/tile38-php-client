<?php

declare(strict_types=1);

namespace Ronappleton\Tile38PhpClient\Commands;

use Ronappleton\Tile38PhpClient\Commands\Abstracts\Command;
use Ronappleton\Tile38PhpClient\Commands\Interfaces\Timeout;
use Ronappleton\Tile38PhpClient\Commands\Traits\HasSearchOptions;
use Ronappleton\Tile38PhpClient\Commands\Traits\HasTimeout;

class Within extends Command implements Timeout
{
    use HasSearchOptions;
    use HasTimeout;

    protected string $command = 'WITHIN';

    protected int $argumentCountRequired = 2;
}
