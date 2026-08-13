<?php

declare(strict_types=1);

namespace Ronappleton\Tile38PhpClient\Commands\Webhook;

use Ronappleton\Tile38PhpClient\Commands\Abstracts\Command;
use Ronappleton\Tile38PhpClient\Commands\Traits\HasSearchOptions;

class Sethook extends Command
{
    use HasSearchOptions;

    protected string $command = 'SETHOOK';

    protected int $argumentCountRequired = 5;

    /**
     * @var array<int, mixed>
     */
    protected array $extras = [];

    public function meta(string $name, mixed $value): static
    {
        $this->extras[] = ['META', $name, $value];

        return $this;
    }

    public function ex(int $seconds): static
    {
        $this->extras[] = ['EX', $seconds];

        return $this;
    }

    /**
     * @return array<int, mixed>
     */
    protected function buildArguments(): array
    {
        $prefix = [
            $this->arguments[0],
            $this->arguments[1],
            ... $this->extras,
            $this->arguments[2],
        ];

        $search = $this->buildSearchArguments($this->arguments[3], $this->arguments[4]);

        return $this->formatArguments([... $prefix, ... $search]);
    }
}
