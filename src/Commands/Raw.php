<?php

declare(strict_types=1);

namespace Ronappleton\Tile38PhpClient\Commands;

use Redis;
use Ronappleton\Tile38PhpClient\Clients\Tile38;
use Ronappleton\Tile38PhpClient\Exceptions\MissingArgument;
use Ronappleton\Tile38PhpClient\Commands\Abstracts\Command;

class Raw extends Command
{
    public function __construct(private readonly Tile38 $client, private readonly array $arguments = [])
    {
    }

    public function execute(): Redis|array|string|bool
    {
        if (!isset($this->arguments[0])) {
            throw new MissingArgument('the raw command should be sent');
        }
        
        $otherArguments = array_slice($this->arguments, 1) ?? '';
        
        return $this->client->rawCommand($this->formatCommand($this->arguments[0]), implode(' ', $otherArguments));
    }
}
