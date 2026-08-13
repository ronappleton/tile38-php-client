<?php

declare(strict_types=1);

namespace Ronappleton\Tile38PhpClient\Exceptions;

use RuntimeException;
use RonAppleton\GeoJson\Enums\GeoJsonType;

use function implode;
use function sprintf;

class GeoJson extends RuntimeException
{
    public static function InvalidType(string $type): self
    {
        return new self(
            sprintf(
                'Invalid GeoJSON type passed [%s], expected one of %s',
                $type,
                implode(', ', GeoJsonType::values()),
            ),
        );
    }
}
