<?php

declare(strict_types=1);

namespace Ronappleton\Tile38PhpClient\Commands\Objects;

use Ronappleton\Tile38PhpClient\Commands\Interfaces\Stringable;

class GeoHash implements Stringable
{
    private function __construct(private readonly string $hash)
    {
    }
    
    public static function make(string $hash): GeoHash
    {
        return new self($hash);
    }
    
    public function toString(): string
    {
        return sprintf('HASH %s', $this->hash);
    }
}
