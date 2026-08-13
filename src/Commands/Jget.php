<?php

declare(strict_types=1);

namespace Ronappleton\Tile38PhpClient\Commands;

use Ronappleton\Tile38PhpClient\Commands\Abstracts\Command;

class Jget extends Command
{
    protected string $command = 'JGET';

    protected int $argumentCountRequired = 3;

    /**
     * @var array<int, mixed>
     */
    protected array $extras = [];

    public function raw(): static
    {
        $this->extras[] = ['RAW'];

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
