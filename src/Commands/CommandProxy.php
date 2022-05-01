<?php

declare(strict_types=1);

namespace Ronappleton\Tile38PhpClient\Commands;

use Redis;
use Ronappleton\Tile38PhpClient\Clients\Tile38;
use Ronappleton\Tile38PhpClient\Exceptions\BaseCommandDoesNotExecute;
use Ronappleton\Tile38PhpClient\Exceptions\CommandDoesNotExist;
use Ronappleton\Tile38PhpClient\Commands\Abstracts\Command as AbstractCommand;

use function sprintf;
use function ucfirst;
use function class_exists;


class CommandProxy
{
    
    
    public function __construct(protected readonly Tile38 $client)
    {
    }


}
