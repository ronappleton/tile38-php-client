<?php

declare(strict_types=1);

namespace Ronappleton\Tile38PhpClient\Commands\Key;

use Ronappleton\Tile38PhpClient\Commands\Abstracts\Command;

class Get extends Command
{
    protected string $command = 'GET';

    protected int $argumentCountRequired = 2;

    /**
     * @var array<int, mixed>
     */
    protected array $extras = [];

    public function withfields(): static
    {
        $this->extras[] = ['WITHFIELDS'];

        return $this;
    }

    public function object(): static
    {
        $this->extras[] = ['OBJECT'];

        return $this;
    }

    public function point(): static
    {
        $this->extras[] = ['POINT'];

        return $this;
    }

    public function bounds(): static
    {
        $this->extras[] = ['BOUNDS'];

        return $this;
    }

    public function hashes(int $geohash): static
    {
        $this->extras[] = ['HASH', $geohash];

        return $this;
    }

    /**
     * @return array<int, mixed>
     */
    protected function buildArguments(): array
    {
        return $this->formatArguments([... $this->arguments, ... $this->extras]);
    }
}
