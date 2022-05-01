<?php

declare(strict_types=1);

namespace Ronappleton\Tile38PhpClient\Commands\Objects;

use Ronappleton\Tile38PhpClient\Commands\Interfaces\Stringable;

class Point implements Stringable
{
    private function __construct(
        private readonly float $latitude,
        private readonly float $longitude,
        private readonly ?float $z = null,
    ) {
    }

    public static function make(float $latitude, float $longitude, ?float $z = null): Point
    {
        return new self($latitude, $longitude, $z);
    }

    public function toString(): string
    {
        $point = sprintf('POINT %f %f', $this->latitude, $this->longitude);
        
        if ($this->z) {
            return sprintf('%s %s', $point, $this->z);
        }
        
        return $point;
    }
}
