<?php

declare(strict_types=1);

namespace Ronappleton\Tile38PhpClient\Enums;

enum SearchType: string
{
    case Nearby = 'NEARBY';
    case Within = 'WITHIN';
    case Intersects = 'INTERSECTS';
}
