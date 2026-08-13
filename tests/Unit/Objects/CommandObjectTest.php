<?php

declare(strict_types=1);

namespace Ronappleton\Tile38PhpClient\Tests\Unit\Objects;

use PHPUnit\Framework\TestCase;
use RonAppleton\GeoJson\Enums\GeoJsonType;
use RonAppleton\GeoJson\Objects\Factory;
use Ronappleton\Tile38PhpClient\Commands\Objects\Bounds;
use Ronappleton\Tile38PhpClient\Commands\Objects\Circle;
use Ronappleton\Tile38PhpClient\Commands\Objects\GeoHash;
use Ronappleton\Tile38PhpClient\Commands\Objects\GeoJson;
use Ronappleton\Tile38PhpClient\Commands\Objects\Point;
use Ronappleton\Tile38PhpClient\Commands\Objects\QuadKey;
use Ronappleton\Tile38PhpClient\Commands\Objects\Sector;
use Ronappleton\Tile38PhpClient\Commands\Objects\StringValue;
use Ronappleton\Tile38PhpClient\Commands\Objects\Tile;
use Ronappleton\Tile38PhpClient\Exceptions\GeoJson as GeoJsonException;

class CommandObjectTest extends TestCase
{
    public function testPoint(): void
    {
        self::assertSame(['POINT', '33.5123', '-112.2693'], Point::make(33.5123, - 112.2693)->toArguments());
    }

    public function testPointWithZ(): void
    {
        self::assertSame(
            ['POINT', '33.5123', '-112.2693', '245'],
            Point::make(33.5123, - 112.2693, 245.0)->toArguments(),
        );
    }

    public function testBounds(): void
    {
        self::assertSame(
            ['BOUNDS', '33.784', '-112.152', '33.7848', '-112.1512'],
            Bounds::make(33.784, - 112.152, 33.7848, - 112.1512)->toArguments(),
        );
    }

    public function testGeoHash(): void
    {
        self::assertSame(['HASH', '9tbnwg'], GeoHash::make('9tbnwg')->toArguments());
    }

    public function testStringValue(): void
    {
        self::assertSame(['STRING', 'John Denton'], StringValue::make('John Denton')->toArguments());
    }

    public function testCircle(): void
    {
        self::assertSame(
            ['CIRCLE', '33.462', '-112.268', '6000'],
            Circle::make(33.462, - 112.268, 6000.0)->toArguments(),
        );
    }

    public function testTile(): void
    {
        self::assertSame(['TILE', '10', '20', '12'], Tile::make(10, 20, 12)->toArguments());
    }

    public function testQuadKey(): void
    {
        self::assertSame(['QUADKEY', '030222320'], QuadKey::make('030222320')->toArguments());
    }

    public function testSector(): void
    {
        self::assertSame(
            ['SECTOR', '33.462', '-112.268', '6000', '0', '90'],
            Sector::make(33.462, - 112.268, 6000.0, 0.0, 90.0)->toArguments(),
        );
    }

    public function testGeoJsonPointWrapsPositionPrimitive(): void
    {
        $point = Factory::make(GeoJsonType::Point);
        $point->setPoints(- 112.2693, 33.5123);
        $point->setAltitude(245.0);

        self::assertSame(
            ['OBJECT', '{"type":"Point","coordinates":[-112.2693,33.5123,245]}'],
            GeoJson::make($point)->toArguments(),
        );
    }

    public function testGeoJsonLineStringPassthrough(): void
    {
        $lineString = Factory::make(GeoJsonType::LineString);

        $first = Factory::make(GeoJsonType::Point);
        $first->setPoints(- 112.2, 33.4);

        $second = Factory::make(GeoJsonType::Point);
        $second->setPoints(- 112.1, 33.5);

        $lineString->addPoints($first, $second);

        self::assertSame(
            ['OBJECT', '{"type":"LineString","coordinates":[[-112.2,33.4],[-112.1,33.5]]}'],
            GeoJson::make($lineString)->toArguments(),
        );
    }

    public function testGeoJsonBoundingBoxThrows(): void
    {
        $this->expectException(GeoJsonException::class);

        $boundingBox = Factory::make(GeoJsonType::BoundingBox);
        $southwest = Factory::make(GeoJsonType::Point);
        $southwest->setPoints(- 112.2, 33.4);
        $northeast = Factory::make(GeoJsonType::Point);
        $northeast->setPoints(- 112.1, 33.5);
        $boundingBox->setPoints($southwest, $northeast);

        GeoJson::make($boundingBox)->toArguments();
    }
}
