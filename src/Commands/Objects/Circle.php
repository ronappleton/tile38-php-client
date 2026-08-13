<?php

declare(strict_types=1);

namespace Ronappleton\Tile38PhpClient\Commands\Objects;

use Ronappleton\Tile38PhpClient\Commands\Interfaces\CommandObject;

class Circle implements CommandObject
{
    private function __construct(
        private readonly float $latitude,
        private readonly float $longitude,
        private readonly float $meters,
    ) {
    }

    public static function make(float $latitude, float $longitude, float $meters): self
    {
        return new self($latitude, $longitude, $meters);
    }

    /**
     * @return array<int, string>
     */
    public function toArguments(): array
    {
        return [
            'CIRCLE',
            (string) $this->latitude,
            (string) $this->longitude,
            (string) $this->meters,
        ];
    }
}
