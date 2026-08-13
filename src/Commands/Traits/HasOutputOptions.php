<?php

declare(strict_types=1);

namespace Ronappleton\Tile38PhpClient\Commands\Traits;

trait HasOutputOptions
{
    /**
     * @var array<int, mixed>
     */
    protected array $outputOptions = [];

    public function count(): static
    {
        $this->outputOptions[] = ['COUNT'];

        return $this;
    }

    public function ids(): static
    {
        $this->outputOptions[] = ['IDS'];

        return $this;
    }

    public function objects(): static
    {
        $this->outputOptions[] = ['OBJECTS'];

        return $this;
    }

    public function points(): static
    {
        $this->outputOptions[] = ['POINTS'];

        return $this;
    }

    public function bounds(): static
    {
        $this->outputOptions[] = ['BOUNDS'];

        return $this;
    }

    public function hashes(int $precision): static
    {
        $this->outputOptions[] = ['HASHES', $precision];

        return $this;
    }
}
