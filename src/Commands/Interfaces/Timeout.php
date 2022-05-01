<?php

declare(strict_types=1);

namespace Ronappleton\Tile38PhpClient\Commands\Interfaces;

interface Timeout
{
    public function timeout(float $timeout);
}
