<?php

declare(strict_types=1);

namespace Ronappleton\Tile38PhpClient\Commands\Objects;

use Ronappleton\Tile38PhpClient\Commands\Interfaces\CommandObject;

class QuadKey implements CommandObject
{
    private function __construct(private readonly string $quadkey)
    {
    }

    public static function make(string $quadkey): self
    {
        return new self($quadkey);
    }

    /**
     * @return array<int, string>
     */
    public function toArguments(): array
    {
        return [
            'QUADKEY',
            $this->quadkey,
        ];
    }
}
