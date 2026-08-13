<?php

declare(strict_types=1);

namespace Ronappleton\Tile38PhpClient\Commands\Scripting;

use Ronappleton\Tile38PhpClient\Commands\Abstracts\Command;

class EvalScript extends Command
{
    protected string $command = 'EVAL';

    protected int $argumentCountRequired = 2;
}
