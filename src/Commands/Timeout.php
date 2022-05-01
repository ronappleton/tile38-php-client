<?php

declare(strict_types=1);

namespace Ronappleton\Tile38PhpClient\Commands;

class Timeout extends Abstracts\Command
{
    protected string $command = 'TIMEOUT';
    
    protected int $argumentCountRequired = 1;
}
