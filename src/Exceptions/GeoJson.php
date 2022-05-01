<?php

declare(strict_types=1);

namespace Ronappleton\Tile38PhpClient\Exceptions;

use RuntimeException;
use Ronappleton\Tile38PhpClient\Enums\GeoJsonType;

class GeoJson extends RuntimeException
{
    public static function InvalidType(string $type): GeoJson
    {
        return new self(
            sprintf('Invalid GeoJSON type passed [%s], expected one of %s', $type, print_r(GeoJsonType::values(), true))
        );
    }
}
