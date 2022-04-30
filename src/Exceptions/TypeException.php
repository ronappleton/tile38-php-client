<?php

declare(strict_types=1);

namespace Ronappleton\Tile38PhpClient\Exceptions;

use Throwable;

class TypeException extends \RuntimeException
{
    public function __construct(string $type, int $code = 0, ?Throwable $previous = null)
    {
        $message = sprintf('Invalid type [%s] passed to command', $type);
        
        parent::__construct($message, $code, $previous);
    }
}
