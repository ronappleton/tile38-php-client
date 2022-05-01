<?php

declare(strict_types=1);

namespace Ronappleton\Tile38PhpClient\Commands\Objects;

use Ronappleton\Tile38PhpClient\Commands\Interfaces\Stringable;
use Ronappleton\Tile38PhpClient\Enums\GeoJsonType;
use Ronappleton\Tile38PhpClient\Exceptions\GeoJson as GeoJsonException;

class GeoJson implements Stringable
{
    private function __construct(private readonly GeoJsonType $type)
    {
    }

    public static function make(GeoJsonType $type): GeoJson
    {
        return new self($type);
    }

    public function toString(): string
    {
        // TODO: Implement toString() method.
    }
}
