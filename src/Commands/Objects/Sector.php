<?php

declare(strict_types=1);

namespace Ronappleton\Tile38PhpClient\Commands\Objects;

use Ronappleton\Tile38PhpClient\Commands\Interfaces\CommandObject;

class Sector implements CommandObject
{
    private function __construct(
        private readonly float $latitude,
        private readonly float $longitude,
        private readonly float $meters,
        private readonly float $bearing1,
        private readonly float $bearing2,
    ) {
    }

    public static function make(
        float $latitude,
        float $longitude,
        float $meters,
        float $bearing1,
        float $bearing2,
    ): self {
        return new self($latitude, $longitude, $meters, $bearing1, $bearing2);
    }

    /**
     * @return array<int, string>
     */
    public function toArguments(): array
    {
        return [
            'SECTOR',
            (string) $this->latitude,
            (string) $this->longitude,
            (string) $this->meters,
            (string) $this->bearing1,
            (string) $this->bearing2,
        ];
    }
}
