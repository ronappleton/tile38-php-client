<?php

declare(strict_types=1);

namespace Ronappleton\Tile38PhpClient\Commands;

use Redis;

class Chans extends Abstracts\Command
{
    protected string $command = 'CHANS';
    
    protected int $argumentCountRequired = 1;
}
