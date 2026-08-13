<?php

declare(strict_types=1);

namespace Ronappleton\Tile38PhpClient\Commands\Objects;

use Ronappleton\Tile38PhpClient\Commands\Interfaces\CommandObject;

class StringValue implements CommandObject
{
    private function __construct(private readonly string $value)
    {
    }

    public static function make(string $value): self
    {
        return new self($value);
    }

    /**
     * @return array<int, string>
     */
    public function toArguments(): array
    {
        return [
            'STRING',
            $this->value,
        ];
    }
}
