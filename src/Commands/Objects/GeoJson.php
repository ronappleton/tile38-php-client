<?php

declare(strict_types=1);

namespace Ronappleton\Tile38PhpClient\Commands\Objects;

use JsonException;
use RonAppleton\GeoJson\Abstracts\GeoJsonObject as BaseGeoJsonObject;
use RonAppleton\GeoJson\Enums\GeoJsonType;
use Ronappleton\Tile38PhpClient\Commands\Interfaces\CommandObject;
use Ronappleton\Tile38PhpClient\Exceptions\GeoJson as GeoJsonException;

use function json_encode;

use const JSON_THROW_ON_ERROR;

class GeoJson implements CommandObject
{
    private function __construct(private readonly BaseGeoJsonObject $object)
    {
    }

    public static function make(BaseGeoJsonObject $object): self
    {
        return new self($object);
    }

    /**
     * @return array<int, string>
     *
     * @throws GeoJsonException
     * @throws JsonException
     */
    public function toArguments(): array
    {
        return [
            'OBJECT',
            $this->toJson(),
        ];
    }

    /**
     * @throws GeoJsonException
     * @throws JsonException
     */
    private function toJson(): string
    {
        if ($this->object->getType() === GeoJsonType::BoundingBox) {
            throw new GeoJsonException('A bounding box cannot be used as a GeoJSON object.');
        }

        if ($this->object->getType() === GeoJsonType::Point) {
            // phpcs:ignore SlevomatCodingStandard.Arrays.AlphabeticallySortedByKeys -- keep type first for canonical GeoJSON
            return json_encode([
                'type' => 'Point',
                'coordinates' => $this->object->toArray(),
            ], JSON_THROW_ON_ERROR);
        }

        return $this->object->toJson();
    }
}
