<?php

declare(strict_types=1);

namespace Ronappleton\Tile38PhpClient\Commands\Interfaces;

interface CommandObject
{
    /**
     * @return array<int, string>
     */
    public function toArguments(): array;
}
