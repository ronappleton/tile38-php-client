<?php

declare(strict_types=1);

namespace Ronappleton\Tile38PhpClient\Commands;

use Redis;
use Ronappleton\Tile38PhpClient\Clients\Tile38;
use Ronappleton\Tile38PhpClient\Exceptions\MissingArgument;
use Ronappleton\Tile38PhpClient\Commands\Abstracts\Command;

use function array_shift;

class Raw extends Command
{
    public function execute(): Redis|array|string|bool
    {
        if (!isset($this->arguments[0])) {
            throw new MissingArgument('the raw command should be sent');
        }
        
        return $this->client->rawCommand(array_shift($this->arguments), ... $this->formatArguments($this->arguments));
    }
}
