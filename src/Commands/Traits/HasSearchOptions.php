<?php

declare(strict_types=1);

namespace Ronappleton\Tile38PhpClient\Commands\Traits;

trait HasSearchOptions
{
    use HasSearchModifiers;
    use HasOutputOptions;

    /**
     * @return array<int, mixed>
     */
    protected function buildSearchArguments(string $key, mixed $area = null): array
    {
        $arguments = [$key];

        foreach ($this->searchOptions as $option) {
            $arguments[] = $option;
        }

        foreach ($this->outputOptions as $option) {
            $arguments[] = $option;
        }

        if ($area !== null) {
            $arguments[] = $area;
        }

        return $arguments;
    }

    /**
     * @return array<int, mixed>
     */
    protected function buildArguments(): array
    {
        return $this->formatArguments($this->buildSearchArguments($this->arguments[0], $this->arguments[1] ?? null));
    }
}
