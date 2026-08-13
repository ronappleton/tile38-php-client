<?php

declare(strict_types=1);

namespace Ronappleton\Tile38PhpClient\Exceptions;

use RuntimeException;
use Throwable;

use function sprintf;

class CommandDoesNotExist extends RuntimeException
{
    public function __construct(string $command, int $code = 0, ?Throwable $previous = null)
    {
        $message = sprintf('Command [%s] is not supported.', $command);

        parent::__construct($message, $code, $previous);
    }
}
