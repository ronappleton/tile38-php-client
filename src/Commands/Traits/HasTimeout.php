<?php

declare(strict_types=1);

namespace Ronappleton\Tile38PhpClient\Commands\Traits;

trait HasTimeout
{
    protected float $timeout = 0.0;

    public function timeout(float $timeout): static
    {
        $this->timeout = $timeout;

        return $this;
    }

    public function execute(): mixed
    {
        $command = $this->getCommand();
        $arguments = $this->buildArguments();

        if ($this->timeout > 0.0) {
            return $this->client->rawCommand('TIMEOUT', (string) $this->timeout, $command, ... $arguments);
        }

        return $this->client->rawCommand($command, ... $arguments);
    }
}
