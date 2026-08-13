<?php

declare(strict_types=1);

namespace Ronappleton\Tile38PhpClient\Commands\Objects;

use Ronappleton\Tile38PhpClient\Commands\Interfaces\CommandObject;

class Roam implements CommandObject
{
    private function __construct(
        private readonly string $key,
        private readonly string $pattern,
        private readonly float $meters,
    ) {
    }

    public static function make(string $key, string $pattern, float $meters): self
    {
        return new self($key, $pattern, $meters);
    }

    /**
     * @return array<int, string>
     */
    public function toArguments(): array
    {
        return [
            'ROAM',
            $this->key,
            $this->pattern,
            (string) $this->meters,
        ];
    }
}
