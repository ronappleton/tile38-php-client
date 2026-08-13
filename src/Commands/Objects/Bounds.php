<?php

declare(strict_types=1);

namespace Ronappleton\Tile38PhpClient\Commands\Objects;

use Ronappleton\Tile38PhpClient\Commands\Interfaces\CommandObject;

class Bounds implements CommandObject
{
    private function __construct(
        private readonly float $southwestLatitude,
        private readonly float $southwestLongitude,
        private readonly float $northeastLatitude,
        private readonly float $northeastLongitude,
    ) {
    }

    public static function make(
        float $southwestLatitude,
        float $southwestLongitude,
        float $northeastLatitude,
        float $northeastLongitude,
    ): self {
        return new self($southwestLatitude, $southwestLongitude, $northeastLatitude, $northeastLongitude);
    }

    /**
     * @return array<int, string>
     */
    public function toArguments(): array
    {
        return [
            'BOUNDS',
            (string) $this->southwestLatitude,
            (string) $this->southwestLongitude,
            (string) $this->northeastLatitude,
            (string) $this->northeastLongitude,
        ];
    }
}
