<?php

declare(strict_types=1);

namespace Ronappleton\Tile38PhpClient\Commands;

use Ronappleton\Tile38PhpClient\Commands\Abstracts\Command;

class Aofmd5 extends Command
{
    protected string $command = 'AOFMD5';

    protected int $argumentCountRequired = 2;
}
