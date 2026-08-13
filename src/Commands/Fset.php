<?php

declare(strict_types=1);

namespace Ronappleton\Tile38PhpClient\Commands;

use Ronappleton\Tile38PhpClient\Commands\Abstracts\Command;

class Fset extends Command
{
    protected string $command = 'FSET';

    protected int $argumentCountRequired = 4;

    /**
     * @var array<int, mixed>
     */
    protected array $extras = [];

    public function rx(): static
    {
        $this->extras[] = ['RX'];

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
