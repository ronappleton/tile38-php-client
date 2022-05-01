<?php

declare(strict_types=1);

namespace Ronappleton\Tile38PhpClient\Enums;

enum GeoJsonType: string
{
    case Point = 'Point';
    case MultiPoint = 'MultiPoint';
    case LineString = 'LineString';
    case MultiLineString = 'MultiLineString';
    case Polygon = 'Polygon';
    case MultiPolygon = 'MultiPolygon';
    case GeometryCollection = 'GeometryCollection';
    case Feature = 'Feature';
    case FeatureCollection = 'FeatureCollection';
    
    public static function values(): array
    {
        $values = [];
        
        foreach (self::cases() as $case) {
            $values[] = $case->value;
        }
        
        return $values;
    }
}
