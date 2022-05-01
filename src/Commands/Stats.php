<?php

declare(strict_types=1);

namespace Ronappleton\Tile38PhpClient\Commands;

class Stats extends Abstracts\Command
{
    protected string $command = 'STATS';
    
    protected int $argumentCountRequired = 1;
}
