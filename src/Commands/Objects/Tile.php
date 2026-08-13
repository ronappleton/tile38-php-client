<?php

declare(strict_types=1);

namespace Ronappleton\Tile38PhpClient\Commands\Objects;

use Ronappleton\Tile38PhpClient\Commands\Interfaces\CommandObject;

class Tile implements CommandObject
{
    private function __construct(
        private readonly int $x,
        private readonly int $y,
        private readonly int $zoom,
    ) {
    }

    public static function make(int $x, int $y, int $zoom): self
    {
        return new self($x, $y, $zoom);
    }

    /**
     * @return array<int, string>
     */
    public function toArguments(): array
    {
        return [
            'TILE',
            (string) $this->x,
            (string) $this->y,
            (string) $this->zoom,
        ];
    }
}
