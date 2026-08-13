<?php

declare(strict_types=1);

namespace Ronappleton\Tile38PhpClient\Commands\Key;

use Ronappleton\Tile38PhpClient\Commands\Abstracts\Command;

class Set extends Command
{
    protected string $command = 'SET';

    protected int $argumentCountRequired = 3;

    /**
     * @var array<int, mixed>
     */
    protected array $extras = [];

    public function field(string $name, mixed $value): static
    {
        $this->extras[] = ['FIELD', $name, $value];

        return $this;
    }

    public function ex(int $seconds): static
    {
        $this->extras[] = ['EX', $seconds];

        return $this;
    }

    public function nx(): static
    {
        $this->extras[] = ['NX'];

        return $this;
    }

    public function xx(): static
    {
        $this->extras[] = ['XX'];

        return $this;
    }

    public function rx(): static
    {
        $this->extras[] = ['RX'];

        return $this;
    }

    public function execute(): mixed
    {
        return $this->client->rawCommand(
            $this->getCommand(),
            ... $this->formatArguments([
                $this->arguments[0],
                $this->arguments[1],
                ... $this->extras,
                $this->arguments[2],
            ]),
        );
    }
}
