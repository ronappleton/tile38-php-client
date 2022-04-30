<?php

declare(strict_types=1);

namespace Ronappleton\Tile38PhpClient\Commands;

use Redis;
use Ronappleton\Tile38PhpClient\Exceptions\MissingArgument;
use Ronappleton\Tile38PhpClient\Commands\Abstracts\Command;

class Auth extends Command
{
    protected string $command = 'AUTH';
    
    public function execute(): Redis|array|string|bool
    {
        if (!isset($this->arguments[0])) {
            throw new MissingArgument('password');
        }

        return parent::execute();
    }
}
