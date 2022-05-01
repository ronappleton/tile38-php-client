<?php

declare(strict_types=1);

namespace Ronappleton\Tile38PhpClient\Commands\Objects;

use Ronappleton\Tile38PhpClient\Commands\Interfaces\Stringable;

class Bounds implements Stringable
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
    ): Bounds {
        return new self($southwestLatitude, $southwestLongitude, $northeastLatitude, $northeastLongitude);
    }
    
    public function toString(): string
    {
        return sprintf(
            'BOUNDS %f %f %f %f',
            $this->southwestLatitude, 
            $this->southwestLongitude, 
            $this->northeastLatitude, 
            $this->northeastLongitude
        );
    }
}
