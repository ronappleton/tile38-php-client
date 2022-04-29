<?php

declare(strict_types=1);

namespace Ronappleton\Tile38PhpClient\Exceptions;

use RuntimeException;
use Throwable;

use function sprintf;

class CommandDoesNotExist extends RuntimeException
{
    public function __construct(string $commandFqdn, int $code = 0, ?Throwable $previous = null)
    {
        $message = sprintf('Command class [%s] does not exist.', $commandFqdn);
        
        parent::__construct($message, $code, $previous);
    }
}
