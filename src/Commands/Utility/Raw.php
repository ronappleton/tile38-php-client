<?php

declare(strict_types=1);

namespace Ronappleton\Tile38PhpClient\Commands\Utility;

use Ronappleton\Tile38PhpClient\Commands\Abstracts\Command;

use function array_shift;

class Raw extends Command
{
    protected int $argumentCountRequired = 1;

    public function execute(): mixed
    {
        $arguments = $this->formatArguments($this->arguments);
        $command = array_shift($arguments);

        return $this->client->rawCommand((string) $command, ... $arguments);
    }
}
