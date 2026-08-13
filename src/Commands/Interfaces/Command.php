<?php

declare(strict_types=1);

namespace Ronappleton\Tile38PhpClient\Commands\Interfaces;

interface Command
{
    public function execute(): mixed;
}
