<?php

declare(strict_types=1);

namespace Ronappleton\Tile38PhpClient\Commands\Search;

use Ronappleton\Tile38PhpClient\Commands\Abstracts\Command;
use Ronappleton\Tile38PhpClient\Commands\Interfaces\Timeout;
use Ronappleton\Tile38PhpClient\Commands\Traits\HasSearchOptions;
use Ronappleton\Tile38PhpClient\Commands\Traits\HasTimeout;

class Scan extends Command implements Timeout
{
    use HasSearchOptions;
    use HasTimeout;

    protected string $command = 'SCAN';

    protected int $argumentCountRequired = 1;
}
