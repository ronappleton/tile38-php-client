<?php

declare(strict_types=1);

namespace Ronappleton\Tile38PhpClient\Commands\Traits;

use function count;

trait HasSearchOptions
{
    /**
     * @var array<int, mixed>
     */
    protected array $searchOptions = [];

    public function cursor(int $start): static
    {
        $this->searchOptions[] = ['CURSOR', $start];

        return $this;
    }

    public function limit(int $count): static
    {
        $this->searchOptions[] = ['LIMIT', $count];

        return $this;
    }

    public function match(string $pattern): static
    {
        $this->searchOptions[] = ['MATCH', $pattern];

        return $this;
    }

    public function where(string $field, mixed $min, mixed $max): static
    {
        $this->searchOptions[] = ['WHERE', $field, $min, $max];

        return $this;
    }

    /**
     * @param array<int, mixed> $values
     */
    public function wherein(string $field, array $values): static
    {
        $this->searchOptions[] = ['WHEREIN', $field, count($values), ... $values];

        return $this;
    }

    /**
     * @param array<int, mixed> $arguments
     */
    public function whereeval(string $script, array $arguments = []): static
    {
        $this->searchOptions[] = ['WHEREEVAL', $script, count($arguments), ... $arguments];

        return $this;
    }

    /**
     * @param array<int, mixed> $arguments
     */
    public function whereevalsha(string $sha, array $arguments = []): static
    {
        $this->searchOptions[] = ['WHEREEVALSHA', $sha, count($arguments), ... $arguments];

        return $this;
    }

    public function nofields(): static
    {
        $this->searchOptions[] = ['NOFIELDS'];

        return $this;
    }

    public function clip(): static
    {
        $this->searchOptions[] = ['CLIP'];

        return $this;
    }

    public function distance(): static
    {
        $this->searchOptions[] = ['DISTANCE'];

        return $this;
    }

    public function buffer(float $meters): static
    {
        $this->searchOptions[] = ['BUFFER', $meters];

        return $this;
    }

    public function fence(): static
    {
        $this->searchOptions[] = ['FENCE'];

        return $this;
    }

    public function detect(string $what): static
    {
        $this->searchOptions[] = ['DETECT', $what];

        return $this;
    }

    public function commands(string $which): static
    {
        $this->searchOptions[] = ['COMMANDS', $which];

        return $this;
    }

    public function asc(): static
    {
        $this->searchOptions[] = ['ASC'];

        return $this;
    }

    public function desc(): static
    {
        $this->searchOptions[] = ['DESC'];

        return $this;
    }

    public function count(): static
    {
        $this->searchOptions[] = ['COUNT'];

        return $this;
    }

    public function ids(): static
    {
        $this->searchOptions[] = ['IDS'];

        return $this;
    }

    public function objects(): static
    {
        $this->searchOptions[] = ['OBJECTS'];

        return $this;
    }

    public function points(): static
    {
        $this->searchOptions[] = ['POINTS'];

        return $this;
    }

    public function bounds(): static
    {
        $this->searchOptions[] = ['BOUNDS'];

        return $this;
    }

    public function hashes(int $precision): static
    {
        $this->searchOptions[] = ['HASHES', $precision];

        return $this;
    }

    /**
     * @return array<int, mixed>
     */
    protected function buildArguments(): array
    {
        $arguments = [$this->arguments[0]];

        foreach ($this->searchOptions as $option) {
            $arguments[] = $option;
        }

        if (isset($this->arguments[1])) {
            $arguments[] = $this->arguments[1];
        }

        return $this->formatArguments($arguments);
    }
}
